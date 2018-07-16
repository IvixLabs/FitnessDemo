import {Routes, RouterModule} from '@angular/router';
import {ModuleWithProviders} from '@angular/core';
import {UserListComponent} from './user/user-list.component';
import {IndexComponent} from './index/index.component';
import {FitnessClientListComponent} from './fitness-client/fitness-client-list.component';
import {FitnessCoachListComponent} from "./fitness-coach/fitness-coach-list.component";
import {GroupFitnessClassListComponent} from "./group-fitness-class/group-fitness-class-list.component";

export const routes: Routes = [
    {path: '', component: IndexComponent},
    {path: 'user/list', component: UserListComponent},
    {path: 'fitness-client/list', component: FitnessClientListComponent},
    {path: 'fitness-coach/list', component: FitnessCoachListComponent},
    {path: 'group-fitness-class/list', component: GroupFitnessClassListComponent},
];

export const AppRoutes: ModuleWithProviders = RouterModule.forRoot(routes);
