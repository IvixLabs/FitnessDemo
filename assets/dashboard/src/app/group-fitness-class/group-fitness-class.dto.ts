import {FitnessCoachSuggestionDto} from "../fitness-coach/fitness-coach-suggestion.dto";

export interface GroupFitnessClassDto {
    id?: string;
    name?: string;
    description?: string;
    fitnessCoach?: FitnessCoachSuggestionDto
}
