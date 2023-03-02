<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class JsonArrayRule implements Rule
{
    public function __construct()
    {
        
    }

    public function passes($attribute, $value)
    {
        if (!is_array($value)) {
            if (str_contains(':',json_encode($value))){
                return false;
            }
        }
        return true;
    }
    public function message()
    {
        return 'Os valores não correspodem a um array';
    }
}