<?php

namespace App\Services\FormBuilder\Inputs;

use App\Services\FormBuilder\Inputs\Contracts\Input;

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
    public function input() : string
    {
        return \Form::file($this->name, $this->attributes);
    }
}