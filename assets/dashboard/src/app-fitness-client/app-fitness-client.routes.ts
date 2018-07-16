import {Routes, RouterModule} from '@angular/router';
import {ModuleWithProviders} from '@angular/core';
import {FitnessClientProfileComponent} from "./fitness-client-profile/fitness-client-profile.component";
import {GroupFitnessClassListComponent} from "./group-fitness-class/group-fitness-class-list.component";

export const routes: Routes = [
    {path: '', component: FitnessClientProfileComponent},
    {path: 'group-fitness-class-list', component: GroupFitnessClassListComponent},
];

export const AppFitnessClientRoutes: ModuleWithProviders = RouterModule.forRoot(routes);
