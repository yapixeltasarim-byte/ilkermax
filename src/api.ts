import { env } from './env.js';
import type { CreatePropertyPayload, CreatePropertyResponse, UploadImageResponse } from './types.js';

class ApiError extends Error {
    constructor(
        message: string,
        public readonly status: number,
        public readonly details?: unknown,
    ) {
        super(message);
    }
}

async function parseErrorBody(response: Response): Promise<unknown> {
    try {
        return await response.json();
    } catch {
        return null;
    }
}

export async function createProperty(payload: CreatePropertyPayload): Promise<CreatePropertyResponse> {
    const response = await fetch(`${env.apiBaseUrl}/api/properties`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-API-Key': env.apiKey,
        },
        body: JSON.stringify(payload),
    });

    if (!response.ok) {
        throw new ApiError('İlan oluşturulamadı', response.status, await parseErrorBody(response));
    }

    return (await response.json()) as CreatePropertyResponse;
}

export async function uploadImage(uploadUrl: string, buffer: Buffer, filename: string): Promise<UploadImageResponse> {
    const form = new FormData();
    form.append('image', new Blob([new Uint8Array(buffer)]), filename);

    const response = await fetch(uploadUrl, {
        method: 'POST',
        headers: {
            'X-API-Key': env.apiKey,
        },
        body: form,
    });

    if (!response.ok) {
        throw new ApiError('Fotoğraf yüklenemedi', response.status, await parseErrorBody(response));
    }

    return (await response.json()) as UploadImageResponse;
}

export { ApiError };
