export interface User {
    id: number;
    name: string;
    username: string;
    type: string;
    email: string;
    email_verified_at: string;
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

export interface Config {
    logo: string;
    school_name: string;
    activity_type: string;
    activity_title: string;
    activity_title_abbreviation: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    config: Config;
    auth: {
        user: User;
        userTypes: Array<UserType>;
    };
};
