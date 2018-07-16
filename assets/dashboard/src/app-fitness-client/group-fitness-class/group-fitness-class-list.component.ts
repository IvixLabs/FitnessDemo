import {Component, OnInit} from '@angular/core';
import {GroupFitnessClassService} from './group-fitness-class.service';
import {GroupFitnessClassListDto} from './group-fitness-class-list.dto';
import {MessageService} from 'primeng/components/common/messageservice';
import {SelectItem} from "primeng/api";
import {FitnessClientProfileService} from "../fitness-client-profile/fitness-client-profile.service";

@Component({
    templateUrl: './group-fitness-class-list.component.html',
})
export class GroupFitnessClassListComponent implements OnInit {

    items: GroupFitnessClassListDto[] = [];
    totalItems = 0;
    firstItem = 0;
    filters: object = {};
    sorting: object = {field: 'id', order: 1};
    itemsPerPage = 10;
    displayItemDialog = false;
    subscriptionTypes: SelectItem[];

    constructor(
        private groupFitnessClassService: GroupFitnessClassService,
        private fitnessClientProfileService: FitnessClientProfileService,
        private messageService: MessageService,
    ) {
        this.subscriptionTypes = [
            {label: 'Email', value: 1},
            {label: 'SMS', value: 2},
        ];
    }

    ngOnInit() {
        this.loadItems();
    }

    private loadItems() {
        this.groupFitnessClassService.getList(this.firstItem, this.itemsPerPage, this.filters, this.sorting).subscribe(res => {
            this.items = res.items;
            this.totalItems = res.count;
        });
    }

    pageChanged(event: any) {
        this.firstItem = event.first;
        this.itemsPerPage = event.rows;
        this.loadItems();
    }

    filterChanged(event: any) {
        this.filters = event.filters;
        this.firstItem = 0;
        this.loadItems();

    }

    sortingChanged(event: any) {
        this.sorting = event;
        this.loadItems();
    }

    changeSubscription(item: GroupFitnessClassListDto) {
        if (item.subscribed) {
            this.fitnessClientProfileService.changeSubscription(item).subscribe(() => {
            });
        } else {
            this.fitnessClientProfileService.unsubscribe(item).subscribe(() => {
            });
        }
    }

}
