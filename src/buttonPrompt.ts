import { InlineKeyboard, type Context } from 'grammy';
import type { BotConversation } from './types.js';

/**
 * Basit bir buton listesi sorar (sayfalama yok — az sayıda seçenek için, ör. kategori/ilçe).
 */
export async function askButtonChoice(
    conversation: BotConversation,
    ctx: Context,
    question: string,
    options: string[],
    prefix: string,
    perRow = 2,
): Promise<string> {
    const keyboard = new InlineKeyboard();

    options.forEach((option, index) => {
        keyboard.text(option, `${prefix}${option}`);

        if ((index + 1) % perRow === 0) {
            keyboard.row();
        }
    });

    await ctx.reply(question, { reply_markup: keyboard });

    const triggers = options.map((option) => `${prefix}${option}`);
    const choiceCtx = await conversation.waitForCallbackQuery(triggers);
    const selected = choiceCtx.callbackQuery.data.slice(prefix.length);

    await choiceCtx.answerCallbackQuery();
    await choiceCtx.editMessageText(`${question}\n✅ ${selected}`);

    return selected;
}

const PAGE_SIZE = 8;
const NEXT = 'pg:next';
const PREV = 'pg:prev';

/**
 * Çok sayıda seçenek olduğunda (ör. bir ilçenin onlarca mahallesi) sayfalı buton listesi sorar.
 */
export async function askPaginatedChoice(
    conversation: BotConversation,
    ctx: Context,
    questionPrefix: string,
    options: string[],
    prefix: string,
): Promise<string> {
    const totalPages = Math.max(1, Math.ceil(options.length / PAGE_SIZE));
    let page = 0;

    const buildKeyboard = (): InlineKeyboard => {
        const keyboard = new InlineKeyboard();
        const pageItems = options.slice(page * PAGE_SIZE, page * PAGE_SIZE + PAGE_SIZE);

        pageItems.forEach((item, index) => {
            keyboard.text(item, `${prefix}${item}`);

            if ((index + 1) % 2 === 0) {
                keyboard.row();
            }
        });

        if (pageItems.length % 2 !== 0) {
            keyboard.row();
        }

        if (page > 0) keyboard.text('◀ Önceki', PREV);
        if (page < totalPages - 1) keyboard.text('Sonraki ▶', NEXT);
        if (page > 0 || page < totalPages - 1) keyboard.row();

        return keyboard;
    };

    const questionFor = (): string => `${questionPrefix} (sayfa ${page + 1}/${totalPages})`;

    await ctx.reply(questionFor(), { reply_markup: buildKeyboard() });

    while (true) {
        const pageItems = options.slice(page * PAGE_SIZE, page * PAGE_SIZE + PAGE_SIZE);
        const triggers = [...pageItems.map((item) => `${prefix}${item}`), NEXT, PREV];
        const choiceCtx = await conversation.waitForCallbackQuery(triggers);
        const data = choiceCtx.callbackQuery.data;

        if (data === NEXT || data === PREV) {
            page += data === NEXT ? 1 : -1;
            await choiceCtx.answerCallbackQuery();
            await choiceCtx.editMessageText(questionFor(), { reply_markup: buildKeyboard() });
            continue;
        }

        const selected = data.slice(prefix.length);
        await choiceCtx.answerCallbackQuery();
        await choiceCtx.editMessageText(`${questionPrefix}\n✅ ${selected}`);

        return selected;
    }
}
