<?php

namespace App\Services\FormBuilder\Inputs;

use App\Services\FormBuilder\Inputs\Contracts\Input;

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