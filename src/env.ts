import 'dotenv/config';

function required(name: string): string {
    const value = process.env[name];

    if (!value) {
        throw new Error(`Ortam değişkeni eksik: ${name}. .env dosyasını kontrol edin.`);
    }

    return value;
}

export const env = {
    botToken: required('BOT_TOKEN'),
    allowedTelegramIds: (process.env.ALLOWED_TELEGRAM_IDS ?? '')
        .split(',')
        .map((id) => id.trim())
        .filter(Boolean),
    apiBaseUrl: required('API_BASE_URL').replace(/\/$/, ''),
    apiKey: required('API_KEY'),
    siteUrl: required('SITE_URL').replace(/\/$/, ''),
};
