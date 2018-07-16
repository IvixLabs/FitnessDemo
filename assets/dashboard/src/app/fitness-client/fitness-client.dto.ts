import {EnumChoice} from "../common/enum-choice";

export interface FitnessClientDto {
    id?: string;
    firstName?: string;
    middleName?: string;
    lastName?: string;
    birthDate?: string | Date;
    gender?: number | EnumChoice;
    email?: string;
    cellPhone?: string;
    photo?: boolean;
}
