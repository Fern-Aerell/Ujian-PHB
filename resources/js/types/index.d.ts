export interface ObjectWithId {
    id?: number;
}

export interface TimeStamp {
    created_at?: string;
    updated_at?: string;
}

export interface User extends ObjectWithId, TimeStamp {
    name?: string;
    username?: string;
    type?: string;
    email?: string;
    email_verified_at?: string;
    password?: string;
}

export interface PasswordConfirmation {
    password_confirmation?: string
}

export interface UserForm extends User, PasswordConfirmation, MuridForm {}

export interface MuridForm {
    kelas?: number,
    kelas_kategori?: number
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
    semester: string;
    school_year_start: number;
    school_year_end: number;
    exam_date_start: string;
    exam_date_end: string;
    holiday_date: string;
    exam_time_start: string;
    exam_time_end: string;
    slider_images: string;
}

export interface Kelas {
    id: number;
    bilangan: number;
    romawi: string;
    pengucapan: string;
}

export interface KelasKategori {
    id: number;
    kepanjangan: string;
    kependekan: string;
}

export interface Mapel {
    id: number;
    kepanjangan: string;
    kependekan: string;
    tags: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    config: Config;
    auth: {
        user: User;
        userTypes: string[];
    };
};

export enum AnswerType {
    objektif = 'objektif',
    objektifKompleks = 'objektif_kompleks',
    isian = 'isian',
}