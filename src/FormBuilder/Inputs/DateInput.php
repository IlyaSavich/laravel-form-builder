<?php

namespace Savich\FormBuilder\Inputs;

use Savich\FormBuilder\Inputs\Contracts\Input;

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