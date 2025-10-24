<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class DaysOfWeek implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        if (!is_array($value)) {
            $fail('The :attribute must be an array of days of the week.');
            return;
        }

        foreach ($value as $day) {
            if (!in_array(strtolower($day), $days)) {
                $fail("The day '$day' is not a valid day of the week. Valid days are: " . implode(', ', $days) . ".");
                return;
            }
        }
    }
}
