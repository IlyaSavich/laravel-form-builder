<?php

namespace App\Services\FormBuilder\Inputs;

use App\Services\FormBuilder\Inputs\Contracts\Input;

class ResetInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'reset';
    }

    /**
     * @return string
     */
    public function input() : string
    {
        return \Form::reset($this->name, $this->attributes);
    }
}