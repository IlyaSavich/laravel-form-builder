<?php

namespace App\Services\FormBuilder\Inputs;

use App\Services\FormBuilder\Inputs\Contracts\Input;

class DatetimeLocalInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'datetime-local';
    }

    /**
     * @return string
     */
    public function input() : string
    {
        return \Form::datetimeLocal($this->name, $this->value, $this->attributes);
    }
}