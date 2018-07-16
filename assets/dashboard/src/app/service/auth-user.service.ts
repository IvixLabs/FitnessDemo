import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';
import {AuthUserDto} from '../dto/auth-user.dto';

@Injectable()
export class AuthUserService {

    private currentUser: AuthUserDto;

    constructor(private http: HttpClient) {
    }

    getCurrentUser(): Observable<AuthUserDto> {

        return new Observable<AuthUserDto>(observer => {

            if (this.currentUser !== undefined) {
                observer.next(this.currentUser);
                observer.complete();
            } else {
                this.http.get<AuthUserDto>('/api/auth-user/get-current-user.json')
                    .subscribe(currentUser => {
                        this.currentUser = currentUser;
                        observer.next(this.currentUser);
                        observer.complete();
                    });
            }
        });
    }
}
