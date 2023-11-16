<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class passwordRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    private $password;
    private $con_pass;

    public function __construct($password,$con_password) {
        $this->password = $password;
        $this->con_pass  = $con_password;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if($this->password != $this->con_pass){
            $fail('Password Confirmation Does not match!!');
        }
    }
}
