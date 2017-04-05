<?php

namespace Tests\Fixtures;

use Savich\FormBuilder\FormBuilder;
use Savich\FormBuilder\Form as SavichForm;

class Form extends SavichForm
{
    protected $routes = [
        'create' => 'create.route',
    ];

    /**
     * Method implements logic for building form
     * @param FormBuilder $builder
     */
    protected function make(FormBuilder $builder)
    {
        // TODO: Implement make() method.
    }
}