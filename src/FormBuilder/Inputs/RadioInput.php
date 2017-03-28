<?php

namespace App\Services\FormBuilder\Inputs;

use App\Services\FormBuilder\Inputs\Contracts\CheckableInput;

class RadioInput extends CheckableInput
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'radio';
    }

    /**
     * @return string
     */
    public function input() : string
    {
        return \Form::radio($this->name, $this->value, $this->checked, $this->attributes);
    }
}