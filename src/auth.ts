import { env } from './env.js';
import type { BotContext } from './types.js';
import type { NextFunction } from 'grammy';

export async function authMiddleware(ctx: BotContext, next: NextFunction): Promise<void> {
    const userId = ctx.from?.id?.toString();

    if (!userId || !env.allowedTelegramIds.includes(userId)) {
        console.log(`Yetkisiz erişim denemesi — Telegram ID: ${userId} (${ctx.from?.first_name ?? 'bilinmiyor'})`);
        await ctx.reply('Bu botu kullanma yetkiniz yok.');
        return;
    }

    await next();
}
