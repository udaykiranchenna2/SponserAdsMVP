export * from './auth';
export * from './navigation';
export * from './ui';

import type { Auth } from './auth';

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    auth: Auth;
    sidebarOpen: boolean;
    [key: string]: unknown;
};

export interface Banner {
    id: number;
    uuid: string;
    title: string;
    image_url: string;
    target_url: string;
    link_text: string | null;
    status: 'active' | 'inactive';
    status_label: string;
    embed_code: string;
    created_at: string;
}

