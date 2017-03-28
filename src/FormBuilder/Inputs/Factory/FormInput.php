<?php

namespace Savich\FormBuilder\Inputs\Factory;

use Savich\FormBuilder\Inputs\Contracts\Input;

class FormInput
{
    /**
     * Create new input class
     * @param string $namespace
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return mixed
     */
    public function create(string $namespace, string $name, $value = null, array $options = [])
    {
        $this->validate($namespace);

        return new $namespace($name, $value, $options);
    }

    /**
     * Validate input namespace
     * @param string $namespace
     * @return string
     * @throws \Exception
     */
    public function validate(string $namespace) : string
    {
        if (!is_a($namespace, Input::class)) {
            throw new \Exception($namespace . ' must be inheritor of ' . Input::class);
        }

        return $namespace;
    }
}