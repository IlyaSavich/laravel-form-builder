<?php

namespace App\Services\FormBuilder\Inputs;

use App\Services\FormBuilder\Inputs\Contracts\Input;

class DateInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'date';
    }

    /**
     * @return string
     */
    public function input() : string
    {
        return \Form::date($this->name, $this->value, $this->attributes);
    }
}