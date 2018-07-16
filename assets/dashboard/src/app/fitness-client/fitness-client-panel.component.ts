import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {FormErrors} from '../common/form-errors';
import {FitnessClientService} from './fitness-client.service';
import {MenuItem} from 'primeng/api';
import {FitnessClientFormDto} from './fitness-client-form.dto';
import {FitnessClientDto} from './fitness-client.dto';

@Component({
    templateUrl: './fitness-client-panel.component.html',
    selector: 'app-fitness-client-panel'
})
export class FitnessClientPanelComponent implements OnInit {

    formErrors: FormErrors = new FormErrors();

    @Input()
    itemId: string = null;

    item: FitnessClientDto;

    loading = false;

    @Output()
    create: EventEmitter<string> = new EventEmitter();

    @Output()
    update = new EventEmitter();

    tabs: MenuItem[];

    tab: MenuItem;

    form: FitnessClientFormDto;

    photoDate = new Date();

    locale = {
        firstDayOfWeek: 1,
        dayNames: ["Воскресение", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"],
        dayNamesShort: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
        dayNamesMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
        monthNames: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
        monthNamesShort: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Нов", "Дек"],
        today: 'Сегодня',
        clear: 'Отчистить'
    };


    constructor(public entityService: FitnessClientService) {
    }

    ngOnInit(): void {
        this.tabs = [
            {
                id: 'common', label: 'Common', command: (event) => {
                    this.tab = event.item;
                }
            }
        ];
        this.tab = this.tabs[0];

    }

    photoUploaded(event) {
        this.item = JSON.parse(event.xhr.response);
        this.photoDate = new Date();
    }

    removePhoto(entity: FitnessClientDto) {
        if(entity.id) {
            this.entityService.removeFitnessClientPhoto(entity.id)
                .subscribe((item)=> {
                    this.item = item;
                });
        }
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

    // authors: AuthorSuggestionDto[] = [];

    // searchAuthors(event) {
    //    this.authorService.getSuggestions(event.query)
    //        .subscribe(res => {
    //            this.authors = res;
    //        });
    // }
}
