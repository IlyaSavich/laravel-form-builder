<?php

namespace Savich\FormBuilder\Inputs;

use Savich\FormBuilder\Inputs\Contracts\Input;

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