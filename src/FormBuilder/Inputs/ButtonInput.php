<?php

namespace Savich\FormBuilder\Inputs;

use Savich\FormBuilder\Inputs\Contracts\Input;

class ButtonInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'button';
    }

    /**
     * @return string
     */
    public function input() : string
    {
        return \Form::button($this->name, $this->attributes);
    }
}