import {Observable} from 'rxjs';
import {SuggestionDto} from '../dto/suggestion.dto';

export interface SuggestionService {

    getSuggestions(query: string, start: number, limit: number): Observable<{ items: SuggestionDto[], total: number }>;
}
