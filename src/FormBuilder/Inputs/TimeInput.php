<?php

namespace App\Services\FormBuilder\Inputs;

use App\Services\FormBuilder\Inputs\Contracts\Input;

class TimeInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'time';
    }

    /**
     * @return string
     */
    public function input() : string
    {
        return \Form::time($this->name, $this->value, $this->attributes);
    }
}