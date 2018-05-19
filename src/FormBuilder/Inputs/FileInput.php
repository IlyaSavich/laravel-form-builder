<?php

namespace Savich\FormBuilder\Inputs;

use Savich\FormBuilder\Inputs\Contracts\Input;

class FileInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'file';
    }

    /**
     * @return string
     */
    public function input()
    {
        return \Form::file($this->name, $this->attributes);
    }
}