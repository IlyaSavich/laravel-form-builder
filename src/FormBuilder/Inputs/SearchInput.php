<?php

namespace Savich\FormBuilder\Inputs;

use Savich\FormBuilder\Inputs\Contracts\Input;

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
    public function input()
    {
        return \Form::input('search', $this->name, $this->value, $this->attributes);
    }
}