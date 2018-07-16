import {Component, OnInit} from '@angular/core';
import {FitnessClientProfileService} from "./fitness-client-profile.service";
import {FitnessClientProfileDto} from "./fitness-client-profile.dto";
import {FormErrors} from "../../app/common/form-errors";
import {ChangePasswordDto} from "./change-password.dto";
import {MessageService} from "primeng/components/common/messageservice";

@Component({
    templateUrl: './fitness-client-profile.component.html',
})
export class FitnessClientProfileComponent implements OnInit {

    profile: FitnessClientProfileDto = {};

    changePassword: ChangePasswordDto = {};

    formErrors: FormErrors = new FormErrors();

    loading = false;

    constructor(
        private profileService: FitnessClientProfileService,
        private messageService: MessageService
    ) {
    }

    ngOnInit() {
        this.profileService.getProfile()
            .subscribe((profile: FitnessClientProfileDto) => {
                this.profile = profile;
            });
    }

    onSubmit() {
        this.loading = true;

        this.profileService.changePassword(this.changePassword)
            .subscribe(
                () => {
                    this.loading = false;
                    this.messageService.add(
                        {
                            severity: 'success',
                            summary: 'Пароль изменён',
                            detail: '&nbsp;'
                        }
                    );
                    this.changePassword = {};
                },
                formErrors => {
                    this.changePassword = {};
                    this.loading = false;
                    setTimeout(() => {
                        this.formErrors = formErrors.error;
                    });

                }
            );
    }
}
