<?php

namespace Savich\FormBuilder\Inputs;

use Savich\FormBuilder\Inputs\Contracts\Input;

class UrlInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'url';
    }

    /**
     * @return string
     */
    public function input() : string
    {
        return \Form::url($this->name, $this->value, $this->attributes);
    }
}