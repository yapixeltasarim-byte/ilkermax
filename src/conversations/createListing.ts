import { InlineKeyboard, type Context } from 'grammy';
import { env } from '../env.js';
import { ApiError, createProperty, uploadImage } from '../api.js';
import type { BotConversation } from '../types.js';

function parsePrice(text: string): number | null {
    const digitsOnly = text.replace(/[^\d]/g, '');
    const price = Number(digitsOnly);

    return digitsOnly && price > 0 ? price : null;
}

function parseLocation(text: string): { province: string; district: string; neighborhood: string } | null {
    const parts = text
        .split(',')
        .map((part) => part.trim())
        .filter(Boolean);

    if (parts.length < 3) {
        return null;
    }

    const [province, district, neighborhood] = parts;
    return { province, district, neighborhood };
}

function parsePositiveInt(text: string): number | null {
    const value = Number(text.trim());

    return Number.isInteger(value) && value > 0 ? value : null;
}

export async function createListing(conversation: BotConversation, ctx: Context): Promise<void> {
    await ctx.reply('Yeni ilan giriyoruz. İstediğiniz zaman /iptal yazarak vazgeçebilirsiniz.');

    const listingTypeKeyboard = new InlineKeyboard()
        .text('🏠 Satılık', 'listing_type:sale')
        .text('🔑 Kiralık', 'listing_type:rent');

    await ctx.reply('İlan tipi?', { reply_markup: listingTypeKeyboard });
    const listingTypeCtx = await conversation.waitForCallbackQuery(['listing_type:sale', 'listing_type:rent']);
    const listingType: 'sale' | 'rent' = listingTypeCtx.callbackQuery.data === 'listing_type:sale' ? 'sale' : 'rent';

    await listingTypeCtx.answerCallbackQuery();
    await listingTypeCtx.editMessageText(`İlan tipi: ${listingType === 'sale' ? '🏠 Satılık' : '🔑 Kiralık'}`);

    let price: number | null = null;
    while (!price) {
        await ctx.reply('Fiyat? (sadece sayı, ör. 4500000)');
        const { message } = await conversation.waitFor('message:text');
        price = parsePrice(message.text);

        if (!price) {
            await ctx.reply('Geçerli bir fiyat girin (sadece rakam).');
        }
    }

    let location: { province: string; district: string; neighborhood: string } | null = null;
    while (!location) {
        await ctx.reply('Konum? (il, ilçe, mahalle — virgülle ayırın, ör: Kocaeli, İzmit, Yenişehir)');
        const { message } = await conversation.waitFor('message:text');
        location = parseLocation(message.text);

        if (!location) {
            await ctx.reply('Lütfen il, ilçe ve mahalleyi virgülle ayırarak yazın (ör: Kocaeli, İzmit, Yenişehir).');
        }
    }

    await ctx.reply('Oda sayısı? (ör. 3+1)');
    const roomsMsg = await conversation.waitFor('message:text');
    const rooms = roomsMsg.message.text.trim();

    let areaNet: number | null = null;
    while (!areaNet) {
        await ctx.reply('Net m²?');
        const { message } = await conversation.waitFor('message:text');
        areaNet = parsePositiveInt(message.text);

        if (!areaNet) {
            await ctx.reply('Geçerli bir m² değeri girin (ör. 120).');
        }
    }

    await ctx.reply('Açıklama?');
    const descriptionMsg = await conversation.waitFor('message:text');
    const description = descriptionMsg.message.text.trim();

    await ctx.reply('İlan oluşturuluyor...');

    let created;
    try {
        created = await conversation.external(() =>
            createProperty({
                listing_type: listingType,
                price: price!,
                province: location!.province,
                district: location!.district,
                neighborhood: location!.neighborhood,
                rooms,
                area_net: areaNet!,
                description,
            }),
        );
    } catch (error) {
        console.error('createProperty hatası:', error);
        const detail = error instanceof ApiError ? ` (${error.status})` : '';
        await ctx.reply(`İlan oluşturulamadı${detail}. Lütfen daha sonra tekrar deneyin.`);
        return;
    }

    await ctx.reply('İlan oluşturuldu. Şimdi fotoğrafları gönderin (istediğiniz kadar, art arda), bitince /bitir yazın.');

    // Önce sadece fotoğrafların file_id'lerini topluyoruz (hızlı, ağ isteği yok).
    // İndirme/yükleme gibi yavaş işleri bekleme döngüsünün İÇİNE koymak, art arda gelen
    // fotoğrafların conversations eklentisinin "replay" mekanizmasıyla çakışmasına
    // (ve sonraki fotoğrafların kaybolmasına) yol açıyordu — bu yüzden ayrıştırdık.
    const fileIds: string[] = [];

    while (true) {
        const photoCtx = await conversation.waitFor(['message:photo', 'message:document', 'message:text']);

        if (photoCtx.message.text === '/bitir') {
            break;
        }

        const fileId =
            photoCtx.message.photo?.at(-1)?.file_id ??
            (photoCtx.message.document?.mime_type?.startsWith('image/') ? photoCtx.message.document.file_id : undefined);

        if (!fileId) {
            await ctx.reply('Bu bir fotoğraf değil. Fotoğraf gönderin veya bitirmek için /bitir yazın.');
            continue;
        }

        fileIds.push(fileId);
        await ctx.reply(`📷 Fotoğraf alındı (${fileIds.length}). Devam edebilir veya /bitir yazabilirsiniz.`);
    }

    let uploadedCount = 0;

    if (fileIds.length > 0) {
        await ctx.reply(`${fileIds.length} fotoğraf yükleniyor...`);

        const results = await conversation.external(async () => {
            const outcomes: boolean[] = [];

            for (const fileId of fileIds) {
                try {
                    const file = await ctx.api.getFile(fileId);
                    const fileUrl = `https://api.telegram.org/file/bot${env.botToken}/${file.file_path}`;
                    const response = await fetch(fileUrl);
                    const buffer = Buffer.from(await response.arrayBuffer());

                    await uploadImage(created.upload_url, buffer, `photo-${Date.now()}-${outcomes.length}.jpg`);
                    outcomes.push(true);
                } catch (error) {
                    console.error('Fotoğraf yükleme hatası:', error);
                    outcomes.push(false);
                }
            }

            return outcomes;
        });

        uploadedCount = results.filter(Boolean).length;
    }

    const listingUrl = `${env.siteUrl}/ilan/${created.slug}`;
    const failedCount = fileIds.length - uploadedCount;
    const failureNote = failedCount > 0 ? ` (${failedCount} fotoğraf yüklenemedi)` : '';
    await ctx.reply(`İlan yayınlandı! ${uploadedCount} fotoğraf eklendi${failureNote}.\n${listingUrl}`);
}
