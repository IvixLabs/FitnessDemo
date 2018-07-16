import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {UserListDto} from './user-list.dto';
import {UserDto} from './user.dto';
import {Observable} from 'rxjs';
import {UserFormDto} from './user-form.dto';

@Injectable()
export class UserService {

    constructor(private http: HttpClient) {
    }

    getForm(): Observable<UserFormDto> {
        const options = {params: {}};
        return this.http.get<UserFormDto>('/api/user/form.json', options);
    }

    get(id: string): Observable<UserDto> {
        const options = {params: {id: id}};
        return this.http.get<UserDto>('/api/user/get.json', options);
    }

    getList(start: number, limit: number, filters: object, sorting: object): Observable<{ count: number, items: UserListDto[] }> {
        const options = {
            params:
                {
                    start: start.toString(),
                    limit: limit.toString(),
                    filters: JSON.stringify(filters),
                    sorting: JSON.stringify(sorting),
                }
        };
        return this.http.get<{ count: number, items: UserListDto[] }>('/api/user/list.json', options);
    }

    create(user: UserDto): Observable<UserDto> {
        return this.http.post<UserDto>('/api/user/create.json', JSON.stringify(user));
    }

    update(user: UserDto): Observable<UserDto> {
        const options = {params: {id: user.id.toString()}};
        return this.http.post<UserDto>('/api/user/update.json', JSON.stringify(user), options);
    }


    delete(id: string): Observable<any> {
        const options = {params: {id: id}};
        return this.http.delete<any>('/api/user/delete.json', options);
    }
}
