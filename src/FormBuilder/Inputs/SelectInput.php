<?php

namespace Savich\FormBuilder\Inputs;

use Savich\FormBuilder\Inputs\Contracts\SelectableInput;

class SelectInput extends SelectableInput
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'select';
    }

    /**
     * @return string
     */
    public function input() : string
    {
        return \Form::select($this->name, $this->list, $this->value, $this->attributes);
    }
}