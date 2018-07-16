import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {FormErrors} from '../common/form-errors';
import {UserService} from './user.service';
import {MenuItem} from 'primeng/api';
import {UserFormDto} from './user-form.dto';
import {UserDto} from './user.dto';

@Component({
    templateUrl: './user-panel.component.html',
    selector: 'app-user-panel'
})
export class UserPanelComponent implements OnInit {

    private formErrors: FormErrors = new FormErrors();

    @Input()
    itemId: string = null;

    item: UserDto;

    private loading = false;

    @Output()
    create: EventEmitter<string> = new EventEmitter();

    @Output()
    update = new EventEmitter();

    private tabs: MenuItem[];

    private tab: MenuItem;

    form: UserFormDto;

    constructor(private entityService: UserService) {
    }

    ngOnInit(): void {
        this.tabs = [
            {
                id: 'user', label: 'Common', icon: 'fa fa-user', command: (event) => {
                    this.tab = event.item;
                }
            },
            {
                id: 'roles', label: 'Security', icon: 'fa fa-lock', command: (event) => {
                    this.tab = event.item;
                }
            },
        ];
        this.tab = this.tabs[0];

    }

    clearItem() {
        this.item = undefined;
    }

    initItem() {
        if (this.form === undefined) {
            this.entityService.getForm()
                .subscribe((form) => {
                    this.form = form;
                });
        }

        this.tab = this.tabs[0];
        if (this.itemId !== null) {
            this.entityService.get(this.itemId)
                .subscribe((item) => {
                    this.item = item;
                });
        } else {
            this.item = {};
        }
    }

    onSubmit() {
        this.loading = true;

        if (this.itemId !== null) {

            this.entityService.update(this.item)
                .subscribe((item) => {
                        this.loading = false;
                        this.item = item;
                        this.update.emit();
                    },
                    formErrors => {
                        this.loading = false;
                        this.formErrors = formErrors.error;
                    }
                );

        } else {

            this.entityService.create(this.item)
                .subscribe(
                    (item) => {
                        this.loading = false;
                        this.item = item;
                        this.create.emit(item.id);
                    },
                    formErrors => {
                        this.loading = false;
                        this.formErrors = formErrors.error;
                    }
                );

        }
    }
}
