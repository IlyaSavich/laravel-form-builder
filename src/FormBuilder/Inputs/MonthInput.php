<?php

namespace Savich\FormBuilder\Inputs;

use Savich\FormBuilder\Inputs\Contracts\Input;

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
    public function input()
    {
        return \Form::input($this->type(), $this->name, $this->value, $this->attributes);
    }
}