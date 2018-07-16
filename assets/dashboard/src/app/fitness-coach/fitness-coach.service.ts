import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {FitnessCoachListDto} from './fitness-coach-list.dto';
import {FitnessCoachDto} from './fitness-coach.dto';
import {Observable} from 'rxjs';
import {FitnessCoachFormDto} from './fitness-coach-form.dto';
import {FitnessCoachSuggestionDto} from './fitness-coach-suggestion.dto';

@Injectable()
export class FitnessCoachService {

    constructor(private http: HttpClient) {
    }

    getForm(): Observable<FitnessCoachFormDto> {
        const options = {params: {}};
        return this.http.get<FitnessCoachFormDto>('/api/fitness-coach/form.json', options);
    }

    get(id: string): Observable<FitnessCoachDto> {
        const options = {params: {id: id}};
        return this.http.get<FitnessCoachDto>('/api/fitness-coach/get.json', options);
    }

    getList(start: number, limit: number, filters: object, sorting: object): Observable<{ count: number, items: FitnessCoachListDto[] }> {
        const options = {params:
                {
                    start: start.toString(),
                    limit: limit.toString(),
                    filters: JSON.stringify(filters),
                    sorting: JSON.stringify(sorting),
                }
        };
        return this.http.get<{ count: number, items: FitnessCoachListDto[] }>('/api/fitness-coach/list.json', options);
    }

    getSuggestions(query: string): Observable<FitnessCoachSuggestionDto[]> {

        const options = {params:
                {
                    query: query,
                }
        };
        return this.http.get<FitnessCoachSuggestionDto[]>('/api/fitness-coach/suggestions.json', options);
    }

    create(entity: FitnessCoachDto): Observable<FitnessCoachDto> {
        return this.http.post<FitnessCoachDto>('/api/fitness-coach/create.json', JSON.stringify(entity));
    }

    update(entity: FitnessCoachDto): Observable<FitnessCoachDto> {
        const options = {params: {id: entity.id.toString()}};
        return this.http.post<FitnessCoachDto>('/api/fitness-coach/update.json', JSON.stringify(entity), options);
    }

    delete(id: string): Observable<any> {
        const options = {params: {id: id}};
        return this.http.delete<any>('/api/fitness-coach/delete.json', options);
    }
}
