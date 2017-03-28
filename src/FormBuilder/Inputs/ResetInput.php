<?php

namespace Savich\FormBuilder\Inputs;

use Savich\FormBuilder\Inputs\Contracts\Input;

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