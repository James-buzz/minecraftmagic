export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
};

export interface ArtStyle {
    identifier: number;
    displayName: string;
}

export interface ArtType {
    identifier: number;
    displayName: string;
    description: string;
}
