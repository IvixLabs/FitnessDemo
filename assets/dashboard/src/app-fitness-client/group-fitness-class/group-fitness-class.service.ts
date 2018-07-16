import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {GroupFitnessClassListDto} from './group-fitness-class-list.dto';
import {Observable} from 'rxjs';

@Injectable()
export class GroupFitnessClassService {

    constructor(private http: HttpClient) {
    }

    getList(start: number, limit: number, filters: object, sorting: object): Observable<{ count: number, items: GroupFitnessClassListDto[] }> {
        const options = {
            params:
                {
                    start: start.toString(),
                    limit: limit.toString(),
                    filters: JSON.stringify(filters),
                    sorting: JSON.stringify(sorting),
                }
        };
        return this.http.get<{ count: number, items: GroupFitnessClassListDto[] }>('/api/fitness-client-profile/group-fitness-class-list.json', options);
    }
}
