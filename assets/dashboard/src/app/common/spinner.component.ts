import {Component, OnInit} from '@angular/core';

@Component({
    template: '<div class="spinner">' +
    '<div class="bounce1"></div>' +
    '<div class="bounce2"></div>' +
    '<div class="bounce3"></div>' +
    '</div>',
    selector: 'app-spinner',
    styleUrls: ['./spinner.component.css'],
})
export class SpinnerComponent implements OnInit {

    ngOnInit() {
    }
}
