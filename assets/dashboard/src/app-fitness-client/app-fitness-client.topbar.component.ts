import {Component, OnInit} from '@angular/core';
import {AppFitnessClientComponent} from "./app-fitness-client.component";
import {AuthUserService} from "../app/service/auth-user.service";
import {AuthUserDto} from "../app/dto/auth-user.dto";

@Component({
    selector: 'app-fitness-client-topbar',
    templateUrl: './app-fitness-client.topbar.component.html'
})
export class AppFitnessClientTopBarComponent implements OnInit {

    private currentUser: AuthUserDto;

    constructor(
        public app: AppFitnessClientComponent,
        private authUserService: AuthUserService
    ) {
    }

    ngOnInit(): void {
        this.authUserService.getCurrentUser().subscribe(user => this.currentUser = user);
    }


}
