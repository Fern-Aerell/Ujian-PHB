import { IIdTable } from "./table";
import { EUserType } from "./enum";

export interface IUser extends IIdTable {
    type: EUserType;
    name: string;
    username: string;
    email: string;
    email_verified_at: string;
    password: string;
    admin: IAdmin | null;
    guru: IGuru | null;
    murid: IMurid | null;
}

export interface IKepanjanganAndKependekan {
    kepanjangan: string;
    kependekan: string;
}

export interface IMapel extends IIdTable, IKepanjanganAndKependekan {}

export interface IKelasKategori extends IIdTable, IKepanjanganAndKependekan {}

export interface IKelas extends IIdTable {
    bilangan: number;
    romawi: string;
    pengucapan: string;
}

export interface IAdmin {}

export interface IGuru {
    guru_mapel_kelas_kategori_kelas: IGuruMapelKelasKategoriKelas[];
}

export interface IGuruMapelKelasKategoriKelas {
    mapel: IMapel;
    kelas_kategori: IKelasKategori;
    kelas: IKelas;
}

export interface IMurid {
    kelas: IKelas;
    kelas_kategori: IKelasKategori;
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

export interface IUserListResponse {
    current_page: number;
    data: IUser[];
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

export interface IUserEditorForm {
    // User
    type: EUserType,
    name: string,
    username: string,
    email: string,
    email_verified_at: string,
    password: string,
    password_confirmation: string,
    // Murid
    murid_kelas_id: null | number,
    murid_kelas_kategori_id: null | number,
    // Guru
    guru_mapel_kelas_kategori_kelas: null | IGuruMapelKelasKategoriKelas[]
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    config: IConfig;
    auth: {
        user: IUser;
    };
};