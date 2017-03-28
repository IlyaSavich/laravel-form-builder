<?php

namespace Savich\FormBuilder\Inputs;

use Savich\FormBuilder\Inputs\Contracts\Input;

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