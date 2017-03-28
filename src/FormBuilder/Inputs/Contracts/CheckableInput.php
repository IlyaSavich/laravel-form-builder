<?php

namespace Savich\FormBuilder\Inputs\Contracts;

abstract class CheckableInput extends Input
{
    /**
     * @var bool
     */
    protected $checked = false;

    /**
     * Set checked value
     * @param bool $checked
     * @return static
     */
    public function checked(bool $checked = true)
    {
        $this->checked = $checked;

        return $this;
    }
}