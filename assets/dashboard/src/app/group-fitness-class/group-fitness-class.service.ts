import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {GroupFitnessClassListDto} from './group-fitness-class-list.dto';
import {GroupFitnessClassDto} from './group-fitness-class.dto';
import {Observable} from 'rxjs';
import {GroupFitnessClassFormDto} from './group-fitness-class-form.dto';
import {GroupFitnessClassSuggestionDto} from './group-fitness-class-suggestion.dto';
import {GroupFitnessClassMessageDto} from "./group-fitness-class-message.dto";

@Injectable()
export class GroupFitnessClassService {

    constructor(private http: HttpClient) {
    }

    getForm(): Observable<GroupFitnessClassFormDto> {
        const options = {params: {}};
        return this.http.get<GroupFitnessClassFormDto>('/api/group-fitness-class/form.json', options);
    }

    get(id: string): Observable<GroupFitnessClassDto> {
        const options = {params: {id: id}};
        return this.http.get<GroupFitnessClassDto>('/api/group-fitness-class/get.json', options);
    }

    getList(start: number, limit: number, filters: object, sorting: object): Observable<{ count: number, items: GroupFitnessClassListDto[] }> {
        const options = {params:
                {
                    start: start.toString(),
                    limit: limit.toString(),
                    filters: JSON.stringify(filters),
                    sorting: JSON.stringify(sorting),
                }
        };
        return this.http.get<{ count: number, items: GroupFitnessClassListDto[] }>('/api/group-fitness-class/list.json', options);
    }

    getSuggestions(query: string): Observable<GroupFitnessClassSuggestionDto[]> {

        const options = {params:
                {
                    query: query,
                }
        };
        return this.http.get<GroupFitnessClassSuggestionDto[]>('/api/group-fitness-class/suggestions.json', options);
    }

    create(entity: GroupFitnessClassDto): Observable<GroupFitnessClassDto> {
        return this.http.post<GroupFitnessClassDto>('/api/group-fitness-class/create.json', JSON.stringify(entity));
    }

    update(entity: GroupFitnessClassDto): Observable<GroupFitnessClassDto> {
        const options = {params: {id: entity.id.toString()}};
        return this.http.post<GroupFitnessClassDto>('/api/group-fitness-class/update.json', JSON.stringify(entity), options);
    }

    delete(id: string): Observable<any> {
        const options = {params: {id: id}};
        return this.http.delete<any>('/api/group-fitness-class/delete.json', options);
    }

    sendMessage(entity: GroupFitnessClassDto, message: GroupFitnessClassMessageDto): Observable<any> {
        const options = {params: {id: entity.id}};
        return this.http.post<any>('/api/group-fitness-class/send-message.json', JSON.stringify(message), options);
    }
}
