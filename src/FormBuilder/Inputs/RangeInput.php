<?php

namespace Savich\FormBuilder\Inputs;

use Savich\FormBuilder\Inputs\Contracts\Input;

class RangeInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'range';
    }

    /**
     * @return string
     */
    public function input()
    {
        return \Form::selectRange($this->name, $this->begin(), $this->end(), $this->value, $this->attributes);
    }

    /**
     * Start of range
     * @return string|null
     */
    protected function begin()
    {
        return $this->attributes['begin'] ?? null;
    }

    /**
     * End of range
     * @return string|null
     */
    protected function end()
    {
        return $this->attributes['end'] ?? null;
    }
}