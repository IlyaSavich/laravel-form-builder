<?php

namespace App\Services\FormBuilder\Inputs;

use App\Services\FormBuilder\Inputs\Contracts\Input;

class ColorInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'color';
    }

    /**
     * @return string
     */
    public function input() : string
    {
        return \Form::color($this->name, $this->value, $this->attributes);
    }
}