<?php

namespace App\Rules;

use Closure;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Validation\ValidationRule;

class oldPassword implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    private $password;

    public function __construct($password)
    {
        $this->password = $password;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $data = User::where('id',getAuth()->id)->first();
        if(!Hash::check($this->password, $data->password)){
            $fail('Password is not correct!!');
        }
    }
}
