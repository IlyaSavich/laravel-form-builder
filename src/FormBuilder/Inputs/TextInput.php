<?php

namespace Savich\FormBuilder\Inputs;

use Savich\FormBuilder\Inputs\Contracts\Input;

class TextInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'text';
    }

    /**
     * @return string
     */
    public function input() : string
    {
        return \Form::text($this->name, $this->value, $this->attributes);
    }
}