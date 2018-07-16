import {Component, OnInit} from '@angular/core';
import {UserService} from './user.service';
import {UserListDto} from './user-list.dto';
import {MenuItem} from 'primeng/primeng';
import {ConfirmationService} from 'primeng/api';
import {MessageService} from 'primeng/components/common/messageservice';
import {AuthUserDto} from '../dto/auth-user.dto';
import {AuthUserService} from '../service/auth-user.service';

@Component({
    templateUrl: './user-list.component.html',
    providers: [ConfirmationService]
})
export class UserListComponent implements OnInit {

    items: UserListDto[] = [];
    totalItems = 0;
    firstItem = 0;
    filters: any = {};
    sorting: any = {field: 'username', order: 1};
    itemsPerPage = 10;
    displayItemDialog = false;
    menuItems: MenuItem[];
    itemIdSelected: number = null;
    currentUser: AuthUserDto;

    constructor(
        private entityService: UserService,
        private confirmationService: ConfirmationService,
        private messageService: MessageService,
        private authUserService: AuthUserService
    ) {
    }

    ngOnInit() {
        this.authUserService.getCurrentUser().subscribe(user => this.currentUser = user);
        this.loadItems();
        this.menuItems = [
            {
                label: 'Create new user',
                icon: 'fa fa-user-plus',
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
            return 'New user';
        } else {
            return 'Edit user';
        }
    }

    deleteItem(id: string) {
        this.confirmationService.confirm({
            message: 'Do you want to delete this user?',
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
                                summary: 'Deleting user error',

                                detail: error.error.error.exception[0].message
                            }
                        );
                    });
            },
            reject: () => {
            }
        });
    }
}
