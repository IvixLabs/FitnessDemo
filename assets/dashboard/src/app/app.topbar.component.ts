import {Component, OnInit} from '@angular/core';
import {AppComponent} from './app.component';
import {AuthUserService} from './service/auth-user.service';
import {AuthUserDto} from './dto/auth-user.dto';

@Component({
    selector: 'app-topbar',
    templateUrl: './app.topbar.component.html'
})
export class AppTopBarComponent implements OnInit {

    private currentUser: AuthUserDto;

    constructor(
        public app: AppComponent,
        private authUserService: AuthUserService
    ) {
    }

    ngOnInit(): void {
        this.authUserService.getCurrentUser().subscribe(user => this.currentUser = user);
    }


}
