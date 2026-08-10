import type { Context } from 'grammy';
import type { Conversation, ConversationFlavor } from '@grammyjs/conversations';

export type BotContext = ConversationFlavor<Context>;
export type BotConversation = Conversation<BotContext, Context>;

export interface CreatePropertyPayload {
    listing_type: 'sale' | 'rent';
    price: number;
    province: string;
    district: string;
    neighborhood: string;
    rooms: string;
    area_net: number;
    description: string;
}

export interface CreatePropertyResponse {
    id: number;
    slug: string;
    upload_url: string;
}

export interface UploadImageResponse {
    id: number;
    url: string;
}
