import {enableProdMode} from '@angular/core';
import {platformBrowserDynamic} from '@angular/platform-browser-dynamic';
import {AppModule} from './app/app.module';
import {environment} from './environments/environment';
import {AppFitnessClientModule} from "./app-fitness-client/app-fitness-client.module";

if (environment.production) {
    enableProdMode();
}

declare var MODULE: any;

if (MODULE !== undefined) {
    let module:any;
    if(MODULE === 'AppModule') {
        module = AppModule;
    }
    if(MODULE === 'AppFitnessClientModule') {
        module = AppFitnessClientModule;
    }

    platformBrowserDynamic().bootstrapModule(module)
        .catch(err => console.log(err));

} else {
    platformBrowserDynamic().bootstrapModule(AppModule)
        .catch(err => console.log(err));

}
