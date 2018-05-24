<?php

namespace Savich\FormBuilder\Inputs;

use Savich\FormBuilder\Inputs\Contracts\Input;

class SubmitInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'submit';
    }

    /**
     * @return string
     */
    public function input()
    {
        return \Form::submit($this->value, $this->attributes);
    }
}