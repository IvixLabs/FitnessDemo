import {Component, Input, OnInit} from '@angular/core';
import {NgModel} from '@angular/forms';

@Component({
    templateUrl: './field-errors.component.html',
    selector: 'app-field-errors'
})
export class FieldErrorsComponent implements OnInit {

    @Input()
    field: NgModel;


    ngOnInit() {
    }
}
