<?php

namespace Savich\FormBuilder\Inputs\Contracts;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;

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
    public function options($options)
    {
        if (!\is_array($options) && !$options instanceof \Traversable) {
            throw new UnexpectedTypeException($options, 'array or Traversable');
        }

        $this->list = $options;

        return $this;
    }
}