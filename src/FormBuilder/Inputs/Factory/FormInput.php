<?php

namespace App\Services\FormBuilder\Inputs\Factory;

use App\Services\FormBuilder\Inputs\ButtonInput;
use App\Services\FormBuilder\Inputs\CheckboxInput;
use App\Services\FormBuilder\Inputs\ColorInput;
use App\Services\FormBuilder\Inputs\DateInput;
use App\Services\FormBuilder\Inputs\DatetimeInput;
use App\Services\FormBuilder\Inputs\DatetimeLocalInput;
use App\Services\FormBuilder\Inputs\EmailInput;
use App\Services\FormBuilder\Inputs\FileInput;
use App\Services\FormBuilder\Inputs\HiddenInput;
use App\Services\FormBuilder\Inputs\ImageInput;
use App\Services\FormBuilder\Inputs\MonthInput;
use App\Services\FormBuilder\Inputs\NumberInput;
use App\Services\FormBuilder\Inputs\PasswordInput;
use App\Services\FormBuilder\Inputs\RadioInput;
use App\Services\FormBuilder\Inputs\RangeInput;
use App\Services\FormBuilder\Inputs\ResetInput;
use App\Services\FormBuilder\Inputs\SearchInput;
use App\Services\FormBuilder\Inputs\SelectInput;
use App\Services\FormBuilder\Inputs\SubmitInput;
use App\Services\FormBuilder\Inputs\TelephoneInput;
use App\Services\FormBuilder\Inputs\TextareaInput;
use App\Services\FormBuilder\Inputs\TextInput;
use App\Services\FormBuilder\Inputs\TimeInput;
use App\Services\FormBuilder\Inputs\UrlInput;
use App\Services\FormBuilder\Inputs\WeekInput;

class FormInput
{
    protected $inputs = [
        'button' => ButtonInput::class,
        'checkbox' => CheckboxInput::class,
        'color' => ColorInput::class,
        'date' => DateInput::class,
        'datetime' => DatetimeInput::class,
        'datetimeLocal' => DatetimeLocalInput::class,
        'email' => EmailInput::class,
        'file' => FileInput::class,
        'hidden' => HiddenInput::class,
        'image' => ImageInput::class,
        'month' => MonthInput::class,
        'number' => NumberInput::class,
        'password' => PasswordInput::class,
        'radio' => RadioInput::class,
        'range' => RangeInput::class,
        'reset' => ResetInput::class,
        'search' => SearchInput::class,
        'select' => SelectInput::class,
        'submit' => SubmitInput::class,
        'tel' => TelephoneInput::class,
        'textarea' => TextareaInput::class,
        'text' => TextInput::class,
        'time' => TimeInput::class,
        'url' => UrlInput::class,
        'week' => WeekInput::class,
    ];

    /**
     * Create new input class
     * @param string $type
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return mixed
     */
    public function create($type, $name, $value = null, array $options = [])
    {
        $namespace = $this->inputClass($type);

        return new $namespace($name, $value, $options);
    }

    /**
     * Getting input class namespace
     * @param string $type
     * @return string
     * @throws \Exception
     */
    public function inputClass($type) : string
    {
        if (!array_key_exists($type, $this->inputs)) {
            throw new \Exception('Unsupported type ' . $type);
        }

        return $this->inputs[$type];
    }
}