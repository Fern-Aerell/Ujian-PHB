import { InertiaForm } from "@inertiajs/vue3";

export enum EUserType {
    ADMIN = 'admin',
    GURU = 'guru',
    MURID = 'murid',
}

export interface IObjectWithId {
    id: number;
}

export interface ITimeStamps {
    created_at: string;
    updated_at: string;
}

export interface IPasswordConfirmation {
    password_confirmation: string
}

export interface IUserTable {
    name: string;
    username: string;
    type: EUserType;
    email: string;
    email_verified_at: string;
    password: string;
}

export interface IMuridTable {
    user_id: number,
    kelas_id: number,
    kelas_kategori_id: number
}

export interface IGuruTable {
    guru_mapel_kelas_kategori_kelas: IGuruMapelKelasKategoriKelasTable[]
}

export interface IThreeUserTypeData {
    admin: {} | null,
    guru: IGuruTable | null,
    murid: IMuridTable | null,
}

export interface IUserTableWithIdThreeUserTypeDataAndTimeStamp extends IUserTable, IObjectWithId, IThreeUserTypeData, ITimeStamps {}

export interface IUserForm extends IUserTable, IPasswordConfirmation {
    // Murid
    murid_kelas_id: number | null,
    murid_kelas_kategori_id: number | null
    // Guru
    guru_mapel_kelas_kategori_kelas: IGuruMapelKelasKategoriKelasTable[] | null
}

export interface IMuridTableWithId extends IObjectWithId, IMuridTable {}

export interface IAdminForm {}

export interface IGuruForm {}

export interface IUserMuridForm extends IMuridTable {}

export interface IPaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface IUserListResponse {
    current_page: number;
    data: IUserTableWithIdThreeUserTypeDataAndTimeStamp[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: IPaginationLink[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}

export interface IConfig {
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

export interface IKepanjanganAndKependekan {
    kepanjangan: string;
    kependekan: string;
}

export interface IKelasTable {
    bilangan: number;
    romawi: string;
    pengucapan: string;
}

export interface IKelasKategoriTable extends IKepanjanganAndKependekan {}

export interface IMapelTable extends IKepanjanganAndKependekan {}

export interface IKelasTableWithId extends IObjectWithId, IKelasTable {}

export interface IKelasKategoriTableWithId extends IObjectWithId, IKelasKategoriTable {}

export interface IMapelTableWithId extends IObjectWithId, IMapelTable {}

export interface IGuruMapelKelasKategoriKelasTable {
    mapel_id: number;
    kelas_kategori_id: number;
    kelas_id: number;
}

export interface IGuruMapelKelasKategoriKelasTableCanNull {
    mapel_id: number | null;
    kelas_kategori_id: number | null;
    kelas_id: number | null;
}

export interface IGuruMapelKelasKategoriKelasTableWithId extends IObjectWithId, IGuruMapelKelasKategoriKelasTable {}

export enum EAnswerType {
    objektif = 'objektif',
    objektifKompleks = 'objektif_kompleks',
    isian = 'isian',
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    config: IConfig;
    auth: {
        user: IUserTableWithIdThreeUserTypeDataAndTimeStamp;
    };
};