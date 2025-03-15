<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Nuhsa implements ValidationRule
{

    private function esNuhsaValido($nuhsa){
        if(strlen($nuhsa) != 12 || substr($nuhsa, 0, 2) != 'AN' || !is_numeric(substr($nuhsa, 2))){
            return false;
        }
        $b = (int)substr($nuhsa, 2, 8);
        $c = (int)substr($nuhsa, 10, 2);
        if($b < 10000000)
            $d = $b + 60 * 10000000;
        else
            $d = (int)("60".substr($nuhsa, 2, 8));
        return $d % 97 == $c;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->esNuhsaValido($value)) {
            $fail(__('validation.custom.nuhsa')[$attribute]);
        }
    }
}
