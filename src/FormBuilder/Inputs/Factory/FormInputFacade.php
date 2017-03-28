<?php

namespace App\Services\FormBuilder\Inputs\Factory;

use Illuminate\Support\Facades\Facade;

class FormInputFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FormInput::class;
    }
}