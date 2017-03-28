<?php

namespace App\Services\FormBuilder\Inputs;

use App\Services\FormBuilder\Inputs\Contracts\Input;

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
    public function input() : string
    {
        return \Form::textarea($this->name, $this->value, $this->attributes);
    }
}