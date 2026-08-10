import { Bot } from 'grammy';
import { conversations, createConversation } from '@grammyjs/conversations';
import { env } from './env.js';
import { authMiddleware } from './auth.js';
import { createListing } from './conversations/createListing.js';
import type { BotContext } from './types.js';

const bot = new Bot<BotContext>(env.botToken);

bot.use(conversations());
bot.use(createConversation(createListing, 'createListing'));

bot.use(authMiddleware);

bot.command('start', async (ctx) => {
    await ctx.reply('Merhaba! İlkerMax ilan botuna hoş geldiniz. Yeni bir ilan girmek için /yeniilan yazın.');
});

bot.command('yeniilan', async (ctx) => {
    await ctx.conversation.enter('createListing');
});

bot.on('message', async (ctx) => {
    await ctx.reply('Anlayamadım. Yeni ilan girmek için /yeniilan yazın.');
});

bot.catch((err) => {
    console.error('Bot hatası:', err.error);
});

async function main() {
    await bot.api.setMyCommands([
        { command: 'yeniilan', description: 'Yeni ilan gir' },
        { command: 'iptal', description: 'Devam eden ilan girişini iptal et' },
    ]);

    console.log('İlkerMax bot başlatılıyor...');
    await bot.start({
        onStart: (botInfo) => {
            console.log(`Bot çalışıyor: @${botInfo.username}`);
        },
    });
}

main();
