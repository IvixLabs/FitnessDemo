import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {FitnessClientListDto} from './fitness-client-list.dto';
import {FitnessClientDto} from './fitness-client.dto';
import {Observable} from 'rxjs';
import {FitnessClientFormDto} from './fitness-client-form.dto';
import {FitnessClientSuggestionDto} from './fitness-client-suggestion.dto';
import * as moment from 'moment';
import {EnumChoice} from "../common/enum-choice";

@Injectable()
export class FitnessClientService {

    constructor(private http: HttpClient) {
    }

    getForm(): Observable<FitnessClientFormDto> {
        const options = {params: {}};
        return this.http.get<FitnessClientFormDto>('/api/fitness-client/form.json', options);
    }

    get(id: string): Observable<FitnessClientDto> {
        const options = {params: {id: id}};
        return this.http.get<FitnessClientDto>('/api/fitness-client/get.json', options);
    }

    getList(start: number, limit: number, filters: object, sorting: object): Observable<{ count: number, items: FitnessClientListDto[] }> {
        const options = {
            params:
                {
                    start: start.toString(),
                    limit: limit.toString(),
                    filters: JSON.stringify(filters),
                    sorting: JSON.stringify(sorting),
                }
        };
        return this.http.get<{ count: number, items: FitnessClientListDto[] }>('/api/fitness-client/list.json', options);
    }

    getSuggestions(query: string): Observable<FitnessClientSuggestionDto[]> {

        const options = {
            params:
                {
                    query: query,
                }
        };
        return this.http.get<FitnessClientSuggestionDto[]>('/api/fitness-client/suggestions.json', options);
    }

    create(entity: FitnessClientDto): Observable<FitnessClientDto> {
        const normEntity = this.normalizeEntity(entity);
        return this.http.post<FitnessClientDto>('/api/fitness-client/create.json', JSON.stringify(normEntity));
    }

    update(entity: FitnessClientDto): Observable<FitnessClientDto> {
        const options = {params: {id: entity.id.toString()}};
        const normEntity = this.normalizeEntity(entity);
        return this.http.post<FitnessClientDto>('/api/fitness-client/update.json', JSON.stringify(normEntity), options);
    }

    delete(id: string): Observable<any> {
        const options = {params: {id: id}};
        return this.http.delete<any>('/api/fitness-client/delete.json', options);
    }

    private normalizeEntity(entity: FitnessClientDto): FitnessClientDto {
        const normEntity = Object.assign({}, entity);

        if (entity.birthDate instanceof Date) {
            normEntity.birthDate = moment(entity.birthDate).format('DD-MM-YYYY');
        }


        if (entity.gender !== null &&
            entity.gender !== undefined &&
            typeof entity.gender === 'object') {

            const gender: EnumChoice = entity.gender;
            normEntity.gender = gender.value;
        }

        return normEntity;
    }

    getFitnessClientPhotoUrl(entity: FitnessClientDto, date: Date) {
        if (entity.photo && entity.id) {
            return '/api/fitness-client/photo.png?id=' + entity.id + '&time=' + date.getTime();
        }

        return null;
    }

    getFitnessClientPhotoUploadUrl(entity: FitnessClientDto) {

        return '/api/fitness-client/photo-upload.json?id=' + entity.id;
    }

    removeFitnessClientPhoto(id: string): Observable<FitnessClientDto> {
        const options = {params: {id: id}};
        return this.http.post<FitnessClientDto>('/api/fitness-client/remove-photo.json', null, options);
    }

    changeFitnessClientStatus(entity: FitnessClientListDto) {
        const options = {params: {id: entity.id}};
        const normEntity = {status: entity.enabled};
        return this.http.post<FitnessClientDto>('/api/fitness-client/change-status.json', JSON.stringify(normEntity), options);
    }
}
