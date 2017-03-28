<?php

namespace App\Services\FormBuilder\Inputs;

use App\Services\FormBuilder\Inputs\Contracts\Input;

class PasswordInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'password';
    }

    /**
     * @return string
     */
    public function input() : string
    {
        return \Form::password($this->name, $this->attributes);
    }
}