<?php

namespace Savich\FormBuilder\Inputs;

use Savich\FormBuilder\Inputs\Contracts\Input;

class EmailInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'email';
    }

    /**
     * @return string
     */
    public function input()
    {
        return \Form::email($this->name, $this->value, $this->attributes);
    }
}