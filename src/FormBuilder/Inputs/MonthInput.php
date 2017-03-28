<?php

namespace App\Services\FormBuilder\Inputs;

use App\Services\FormBuilder\Inputs\Contracts\Input;

class MonthInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'month';
    }

    /**
     * @return string
     */
    public function input() : string
    {
        return \Form::input($this->type(), $this->name, $this->value, $this->attributes);
    }
}