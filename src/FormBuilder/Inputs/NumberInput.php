<?php

namespace App\Services\FormBuilder\Inputs;

use App\Services\FormBuilder\Inputs\Contracts\Input;

class NumberInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'number';
    }

    /**
     * @return string
     */
    public function input() : string
    {
        return \Form::number($this->name, $this->value, $this->attributes);
    }
}