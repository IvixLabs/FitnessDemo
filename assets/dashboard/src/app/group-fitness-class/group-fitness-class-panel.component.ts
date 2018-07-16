import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {FormErrors} from '../common/form-errors';
import {GroupFitnessClassService} from './group-fitness-class.service';
import {MenuItem} from 'primeng/api';
import {GroupFitnessClassFormDto} from './group-fitness-class-form.dto';
import {GroupFitnessClassDto} from './group-fitness-class.dto';
import {FitnessCoachService} from "../fitness-coach/fitness-coach.service";
import {GroupFitnessClassMessageDto} from "./group-fitness-class-message.dto";
import {MessageService} from "primeng/components/common/messageservice";

@Component({
    templateUrl: './group-fitness-class-panel.component.html',
    selector: 'app-group-fitness-class-panel'
})
export class GroupFitnessClassPanelComponent implements OnInit {

    formErrors: FormErrors = new FormErrors();
    messageFormErrors: FormErrors = new FormErrors();

    @Input()
    itemId: string = null;

    item: GroupFitnessClassDto;

    loading = false;

    @Output()
    create: EventEmitter<string> = new EventEmitter();

    @Output()
    update = new EventEmitter();

    tabs: MenuItem[];

    tab: MenuItem;

    form: GroupFitnessClassFormDto;

    message: GroupFitnessClassMessageDto = {};

    constructor(
        private entityService: GroupFitnessClassService,
        public fitnessCoachService: FitnessCoachService,
        private messageService: MessageService
    ) {
    }

    ngOnInit(): void {
        this.tabs = [
            {
                id: 'common', label: 'Общие', command: (event) => {
                    this.tab = event.item;
                }
            },
            {
                id: 'message', label: 'Уведомление', command: (event) => {
                    this.tab = event.item;
                }
            }
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

    sendMessage() {
        this.entityService.sendMessage(this.item, this.message)
            .subscribe(() => {
                    this.messageService.add(
                        {
                            severity: 'success',
                            summary: 'Уведомление отправлено',

                            detail: '&nbsp;'
                        }
                    );
                },
                formErrors => {
                    this.loading = false;
                    this.messageFormErrors = formErrors.error;
                });
    }
}
