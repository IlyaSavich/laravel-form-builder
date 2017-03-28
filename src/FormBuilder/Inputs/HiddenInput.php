<?php

namespace App\Services\FormBuilder\Inputs;

use App\Services\FormBuilder\Inputs\Contracts\Input;

class HiddenInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'hidden';
    }

    /**
     * @return string
     */
    public function input() : string
    {
        return \Form::hidden($this->name, $this->value, $this->attributes);
    }
}