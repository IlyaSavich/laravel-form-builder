<?php

namespace App\Services\FormBuilder\Inputs;

use App\Services\FormBuilder\Inputs\Contracts\Input;

class SearchInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'search';
    }

    /**
     * @return string
     */
    public function input() : string
    {
        return \Form::input('search', $this->name, $this->value, $this->attributes);
    }
}