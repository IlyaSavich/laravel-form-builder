<?php

namespace Savich\FormBuilder\Inputs;

use Savich\FormBuilder\Inputs\Contracts\Input;

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
    public function input()
    {
        return \Form::color($this->name, $this->value, $this->attributes);
    }
}