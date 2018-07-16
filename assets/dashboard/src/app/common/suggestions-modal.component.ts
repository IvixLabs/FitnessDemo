import {Component, EventEmitter, forwardRef, Input, OnInit, Output} from '@angular/core';
import {SuggestionDto} from '../dto/suggestion.dto';
import {ControlValueAccessor, NG_VALUE_ACCESSOR} from '@angular/forms';

@Component({
    templateUrl: './suggestions-modal.html',
    selector: 'app-suggestions-modal',
    providers: [{
        provide: NG_VALUE_ACCESSOR,
        useExisting: forwardRef(() => SuggestionsModalComponent),
        multi: true
    }]
})
export class SuggestionsModalComponent implements OnInit, ControlValueAccessor {

    isShow = false;

    @Input()
    items: SuggestionDto[];

    private _value: SuggestionDto[] | SuggestionDto;

    @Output()
    public valueChange: EventEmitter<any> = new EventEmitter<any>();

    private ngModelOnChange: Function;

    @Input()
    public header: string;

    @Input()
    public total = 20;

    @Output()
    lazyLoad: EventEmitter<any> = new EventEmitter();

    @Input()
    public multiple = false;

    ngOnInit() {

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

    registerOnChange(fn: any): void {
        this.ngModelOnChange = fn;
    }

    registerOnTouched(fn: any): void {
    }

    setDisabledState(isDisabled: boolean): void {
    }

    writeValue(obj: any): void {
        this.value = obj;
    }


    public toggle() {
        this.isShow = !this.isShow;
    }

    public select(item: SuggestionDto) {

        if (this.multiple) {
            const selectedItems = this.value as SuggestionDto[];
            for (const selectedItem of selectedItems) {
                if (item.id === selectedItem.id) {
                    return;
                }
            }
            selectedItems.push(item);
        } else {
            this.value = item;
        }
    }

    public remove(item: SuggestionDto) {

        if (this.multiple) {
            const selectedItems = this.value as SuggestionDto[];
            for (const selectedItemIndex in selectedItems) {
                if (item.id === selectedItems[selectedItemIndex].id) {
                    const index = parseInt(selectedItemIndex);
                    selectedItems.splice(index, 1);
                    return;
                }
            }
            selectedItems.push(item);
        } else {
            this.value = undefined;
        }
    }

    public isItemSelected(item: SuggestionDto) {

        if (this.multiple) {
            const selectedItems = this.value as SuggestionDto[];
            for (const selectedItem of selectedItems) {
                if (item.id === selectedItem.id) {
                    return true;
                }
            }
        } else {
            const selectedItem = this.value as SuggestionDto;
            if (selectedItem !== undefined && item.id === selectedItem.id) {
                return true;
            }
        }
        return false;
    }

    public loadData(event) {
        this.lazyLoad.emit(event);
    }
}
