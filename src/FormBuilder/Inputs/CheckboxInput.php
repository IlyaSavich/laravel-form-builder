<?php

namespace App\Services\FormBuilder\Inputs;

use App\Services\FormBuilder\Inputs\Contracts\CheckableInput;

class CheckboxInput extends CheckableInput
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'checkbox';
    }

    /**
     * @return string
     */
    public function input() : string
    {
        return \Form::checkbox($this->name, $this->value, $this->checked, $this->attributes);
    }
}