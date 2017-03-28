<?php

namespace Savich\FormBuilder\Inputs;

use Savich\FormBuilder\Inputs\Contracts\CheckableInput;

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