<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class PhoneNumberNotExist implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $phone = substr($value, -10);

        $verify = User::where('phone', 'LIKE', '%'.$phone)->get();

        if($verify->isEmpty()){

            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This :attribute has already taken';
    }
}
