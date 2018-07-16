import {Component, OnInit} from '@angular/core';
import {FitnessClientService} from './fitness-client.service';
import {FitnessClientListDto} from './fitness-client-list.dto';
import {MenuItem} from 'primeng/primeng';
import {ConfirmationService} from 'primeng/api';
import {MessageService} from 'primeng/components/common/messageservice';

@Component({
    templateUrl: './fitness-client-list.component.html',
    providers: [ConfirmationService]
})
export class FitnessClientListComponent implements OnInit {

    items: FitnessClientListDto[] = [];
    totalItems = 0;
    firstItem = 0;
    filters: object = {};
    sorting: object = {field: 'id', order: 1};
    itemsPerPage = 10;
    displayItemDialog = false;
    menuItems: MenuItem[];
    itemIdSelected: number = null;

    constructor(
        private entityService: FitnessClientService,
        private confirmationService: ConfirmationService,
        private messageService: MessageService,
    ) {
    }

    ngOnInit() {
        this.loadItems();
        this.menuItems = [
            {
                label: 'Создать клиента',
                icon: 'fa fa-plus',
                command: () => {
                    this.itemIdSelected = null;
                    this.showItemDialog();
                },
            }
        ];
    }

    itemCreated(id: number) {
        this.itemIdSelected = id;
        this.loadItems();
    }

    itemUpdated() {
        this.loadItems();
    }

    private loadItems() {
        this.entityService.getList(this.firstItem, this.itemsPerPage, this.filters, this.sorting).subscribe(res => {
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

    updateItem(id: number) {
        this.itemIdSelected = id;
        this.showItemDialog();
    }

    private showItemDialog() {
        this.displayItemDialog = true;
    }

    getItemDialogTitle() {
        if (this.itemIdSelected === null) {
            return 'Новый клиент';
        } else {
            return 'Редактирование клиента';
        }
    }

    deleteItem(id: string) {
        this.confirmationService.confirm({
            message: 'Do you want to delete this item?',
            header: 'Delete confirmation',
            icon: 'fa fa-trash',
            accept: () => {
                this.entityService.delete(id)
                    .subscribe(() => {
                        this.loadItems();
                    }, error => {
                        this.messageService.add(
                            {
                                severity: 'error',
                                summary: 'Deleting item error',

                                detail: error.error.error.exception[0].message
                            }
                        );
                    });
            },
            reject: () => {
            }
        });
    }

    changeStatus(item: FitnessClientListDto) {
        this.entityService.changeFitnessClientStatus(item)
            .subscribe(() => {
            });
    }
}
