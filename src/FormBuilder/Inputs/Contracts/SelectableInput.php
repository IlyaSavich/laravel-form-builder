<?php

namespace App\Services\FormBuilder\Inputs\Contracts;

/**
 * Class SelectableInput
 * @package App\Services\FormBuilder\Inputs\Contracts
 *
 * @property array $list
 */
abstract class SelectableInput extends Input
{
    /**
     * Array of available options
     * @var array
     */
    protected $list;

    /**
     * Set array of available options
     * @param array $options
     * @return static
     */
    public function options(array $options)
    {
        $this->list = $options;

        return $this;
    }
}