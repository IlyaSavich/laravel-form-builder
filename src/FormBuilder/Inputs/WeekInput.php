<?php

namespace App\Services\FormBuilder\Inputs;

use App\Services\FormBuilder\Inputs\Contracts\Input;

class WeekInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'week';
    }

    /**
     * @return string
     */
    public function input() : string
    {
        return \Form::input('week', $this->name, $this->value, $this->attributes);
    }
}