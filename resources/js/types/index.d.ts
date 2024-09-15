export interface User {
    id: number;
    name: string;
    username: string;
    type: string;
    created_at: string;
    updated_at: string;
    password: string;
}

export interface UserType {
    type: string;
}

export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface UserListResponse {
    current_page: number;
    data: User[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: PaginationLink[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
        userTypes: Array<UserType>;
    };
};
