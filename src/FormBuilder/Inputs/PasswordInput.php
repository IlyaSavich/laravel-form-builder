<?php

namespace Savich\FormBuilder\Inputs;

use Savich\FormBuilder\Inputs\Contracts\Input;

class PasswordInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'password';
    }

    /**
     * @return string
     */
    public function input() : string
    {
        return \Form::password($this->name, $this->attributes);
    }
}