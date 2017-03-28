<?php

namespace Savich\FormBuilder\Inputs;

use Savich\FormBuilder\Inputs\Contracts\Input;

class DatetimeInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'datetime';
    }

    /**
     * @return string
     */
    public function input() : string
    {
        return \Form::datetime($this->name, $this->value, $this->attributes);
    }
}