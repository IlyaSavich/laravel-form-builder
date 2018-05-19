<?php

namespace Savich\FormBuilder\Inputs;

use Savich\FormBuilder\Inputs\Contracts\Input;

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
    public function input()
    {
        return \Form::time($this->name, $this->value, $this->attributes);
    }
}