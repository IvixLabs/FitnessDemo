import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';
import {FitnessClientProfileDto} from "./fitness-client-profile.dto";
import {ChangePasswordDto} from "./change-password.dto";
import {GroupFitnessClassListDto} from "../group-fitness-class/group-fitness-class-list.dto";

@Injectable()
export class FitnessClientProfileService {

    constructor(private http: HttpClient) {
    }

    getProfile(): Observable<FitnessClientProfileDto> {
        const options = {params: {}};
        return this.http.get<FitnessClientProfileDto>('/api/fitness-client-profile/data.json', options);
    }

    changePassword(changePassword: ChangePasswordDto): Observable<any> {
        return this.http.post<any>('/api/fitness-client-profile/change-password.json', JSON.stringify(changePassword));
    }

    changeSubscription(listItem: GroupFitnessClassListDto): Observable<any> {
        const data = {classId: listItem.id, subscriptionType: listItem.subscriptionType};
        return this.http.post<any>('/api/fitness-client-profile/change-subscription.json', JSON.stringify(data));
    }

    unsubscribe(listItem: GroupFitnessClassListDto): Observable<any> {
        const data = {classId: listItem.id};
        return this.http.post<any>('/api/fitness-client-profile/unsubscribe.json', JSON.stringify(data));
    }
}
