<?php

namespace Savich\FormBuilder\Inputs;

use Savich\FormBuilder\Inputs\Contracts\Input;

class TextareaInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'textarea';
    }

    /**
     * @return string
     */
    public function input()
    {
        return \Form::textarea($this->name, $this->value, $this->attributes);
    }
}