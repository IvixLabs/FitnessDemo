import {Component, EventEmitter, forwardRef, HostListener, Input, OnInit, Output, ViewChild} from '@angular/core';
import {SuggestionDto} from '../dto/suggestion.dto';
import {SuggestionService} from '../service/suggestion.service';
import {ControlValueAccessor, NG_VALUE_ACCESSOR} from '@angular/forms';
import {AutoComplete} from 'primeng/primeng';

@Component({
    templateUrl: './suggestions-input.html',
    selector: 'app-suggestions-input',
    providers: [{
        provide: NG_VALUE_ACCESSOR,
        useExisting: forwardRef(() => SuggestionsInputComponent),
        multi: true
    }]
})
export class SuggestionsInputComponent implements OnInit, ControlValueAccessor {

    @Input()
    public suggestionService: SuggestionService;

    @Input()
    public name: string;

    @Input()
    public header: string;

    @Input()
    public multiple = false;

    @ViewChild(AutoComplete)
    private autoComplete: AutoComplete;

    suggestions: SuggestionDto[] = [];
    suggestionList: SuggestionDto[] = [];
    suggestionListTotal = 0;

    private _value: SuggestionDto[] | SuggestionDto;

    @Output()
    public valueChange: EventEmitter<any> = new EventEmitter<any>();

    private ngModelOnChange: Function;

    private ngModelOnTouched: Function;

    ngOnInit() {

    }

    registerOnChange(fn: any): void {
        this.ngModelOnChange = fn;
    }

    registerOnTouched(fn: any): void {
        this.ngModelOnTouched = fn;
    }

    setDisabledState(isDisabled: boolean): void {

    }

    writeValue(obj: any): void {
        this.value = obj;
    }

    @HostListener('click')
    click() {
        if (this.ngModelOnTouched) {
            this.ngModelOnTouched();
        }
    }

    @Input()
    get value() {
        return this._value;
    }

    set value(value: SuggestionDto[] | SuggestionDto) {
        if (value === null) {
            return;
        }
        this._value = value;

        if (this.ngModelOnChange) {
            this.ngModelOnChange(this._value);
        }
        this.valueChange.emit(this._value);
    }

    searchSuggestions(event) {
        this.suggestionService.getSuggestions(event.query, 0, 5)
            .subscribe(res => {
                this.suggestions = res.items;

            });
    }

    loadSuggestions(event) {
        this.suggestionService.getSuggestions('', event.first, event.rows)
            .subscribe(res => {
                console.log(res);
                this.suggestionList = res.items;
                this.suggestionListTotal = res.total;
            });
    }
}
