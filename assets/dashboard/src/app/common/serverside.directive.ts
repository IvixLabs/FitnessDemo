import {Directive, forwardRef, Inject, Input, OnInit} from '@angular/core';
import {
    NG_VALIDATORS,
    Validator,
    AbstractControl,
    ValidationErrors,
    NgForm
} from '@angular/forms';
import {isUndefined} from 'util';


@Directive({
    selector: '[appServerside]',
    providers: [{
        provide: NG_VALIDATORS,
        useExisting: forwardRef(() => ServersideValidatorDirective),
        multi: true
    }]
})
export class ServersideValidatorDirective implements Validator, OnInit {

    @Input() name: string;
    private _formErrors: any = {};


    constructor(@Inject(forwardRef(() => NgForm)) private ngForm: NgForm) {
    }


    @Input()
    set appServerside(value: any) {
        this._formErrors = value;
        if (!isUndefined(this.ngForm.controls[this.getName()])) {
            setTimeout(() => {
                this.ngForm.controls[this.getName()].updateValueAndValidity();
            });
        }
    }

    private getName() {
        return this.name;
    }


    ngOnInit(): void {

    }

    valFn(control: AbstractControl): { [key: string]: any } {

        if (isUndefined(this._formErrors.errors) || isUndefined(this._formErrors.errors.children)) {
            return null;
        }


        const controlName = this.getName();
        const formErrors = this._formErrors.errors.children;

        if (
            !isUndefined(formErrors[controlName]) &&
            !isUndefined(formErrors[controlName].errors)
        ) {
            const errors: ValidationErrors = {};
            for (const errorString of formErrors[controlName].errors) {
                if (isUndefined(errors['serverside'])) {
                    errors['serverside'] = [];
                }
                errors['serverside'].push(errorString);
            }

            delete formErrors[controlName].errors;


            return errors;
        }


        return null;
    }

    validate(control: AbstractControl): { [key: string]: any } {
        return this.valFn(control);
    }
}


