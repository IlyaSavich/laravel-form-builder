<?php

namespace Savich\FormBuilder\Inputs;

use Savich\FormBuilder\Inputs\Contracts\Input;

class TelephoneInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'tel';
    }

    /**
     * @return string
     */
    public function input() : string
    {
        return \Form::tel($this->name, $this->value, $this->attributes);
    }
}