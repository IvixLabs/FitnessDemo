import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {FormErrors} from '../common/form-errors';
import {FitnessCoachService} from './fitness-coach.service';
import {MenuItem} from 'primeng/api';
import {FitnessCoachFormDto} from './fitness-coach-form.dto';
import {FitnessCoachDto} from './fitness-coach.dto';

@Component({
    templateUrl: './fitness-coach-panel.component.html',
    selector: 'app-fitness-coach-panel'
})
export class FitnessCoachPanelComponent implements OnInit {

    private formErrors: FormErrors = new FormErrors();

    @Input()
    itemId: string = null;

    private item: FitnessCoachDto;

    private loading = false;

    @Output()
    create: EventEmitter<string> = new EventEmitter();

    @Output()
    update = new EventEmitter();

    private tabs: MenuItem[];

    private tab: MenuItem;

    private form: FitnessCoachFormDto;

    constructor(private entityService: FitnessCoachService) {
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
