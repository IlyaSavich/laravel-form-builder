<?php

namespace App\Services\FormBuilder;

use App\Services\FormBuilder\Inputs\ButtonInput;
use App\Services\FormBuilder\Inputs\CheckboxInput;
use App\Services\FormBuilder\Inputs\ColorInput;
use App\Services\FormBuilder\Inputs\Contracts\Input;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Prophecy\Exception\Doubler\MethodNotFoundException;

/**
 * Class FormBuilder
 * @package App\Services\FormBuilder
 *
 * @method CheckboxInput checkbox($name, $value = null, array $options = [])
 * @method ColorInput color($name, $value = null, array $options = [])
 * @method DateInput date($name, $value = null, array $options = [])
 * @method DatetimeInput datetime($name, $value = null, array $options = [])
 * @method DatetimeLocalInput datetimeLocal($name, $value = null, array $options = [])
 * @method EmailInput email($name, $value = null, array $options = [])
 * @method HiddenInput hidden($name, $value = null, array $options = [])
 * @method MonthInput month($name, $value = null, array $options = [])
 * @method NumberInput number($name, $value = null, array $options = [])
 * @method RadioInput radio($name, $value = null, array $options = [])
 * @method RangeInput range($name, $value = null, array $options = [])
 * @method SearchInput search($name, $value = null, array $options = [])
 * @method SelectInput select($name, $value = null, array $options = [])
 * @method TelephoneInput tel($name, $value = null, array $options = [])
 * @method TextareaInput textarea($name, $value = null, array $options = [])
 * @method TextInput text($name, $value = null, array $options = [])
 * @method TimeInput time($name, $value = null, array $options = [])
 * @method UrlInput url($name, $value = null, array $options = [])
 * @method WeekInput week($name, $value = null, array $options = [])
 */
class FormBuilder
{
    /**
     * Array of inputs
     * @var array
     */
    protected $inputs = [];

    /**
     * Array of form attributes
     * @var array
     */
    protected $attributes = [];

    /**
     * Path to view form
     * @var string
     */
    protected $view;

    /**
     * @var Model|\Eloquent
     */
    protected $model;

    /**
     * Form head in html. Uses to allow to users binding model vars to form inputs
     * @var HtmlString|string
     */
    protected $header;

    public function __construct()
    {
        $this->view = config('form-builder.views.form');
    }

    /**
     * Generate form view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get()
    {
        return view($this->view)->with($this->viewVars());
    }

    /**
     * Generating form
     * @return string
     */
    public function generate()
    {
        return $this->header() . $this->generateGroups() . $this->close();
    }

    /**
     * Set view for render form
     * @param string $view
     * @return static
     */
    public function view(string $view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Add new button input
     * @param string $name
     * @param array $options
     * @return ButtonInput
     */
    public function button($name, array $options = [])
    {
        return $this->add('button', $name, null, $options);
    }

    /**
     * Add new file input
     * @param string $name
     * @param array $options
     * @return FileInput
     */
    public function file($name, array $options = [])
    {
        return $this->add('file', $name, null, $options);
    }

    /**
     * Add new image input
     * @param string $name
     * @param array $options
     * @return ImageInput
     */
    public function image($name, array $options = [])
    {
        return $this->add('image', $name, null, $options);
    }

    /**
     * Add new reset input
     * @param string $name
     * @param array $options
     * @return ResetInput
     */
    public function reset($name, array $options = [])
    {
        return $this->add('reset', $name, null, $options);
    }

    /**
     * Add new password input
     * @param string $name
     * @param array $options
     * @return PasswordInput
     */
    public function password($name, array $options = [])
    {
        return $this->add('password', $name, null, $options);
    }

    /**
     * Add new submit input
     * @param string $name
     * @param array $options
     * @return SubmitInput
     */
    public function submit($name, array $options = [])
    {
        $input = $this->add('submit', $name, null, $options);

        $input->model($this->model);

        return $input;
    }

    /**
     * Adding new input
     * @param string $type
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return Input
     */
    public function add(string $type, string $name, $value = null, array $options = []) : Input
    {
        $input = \FormInput::create($type, $name, $value, $options);

        $this->inputs[] = $input;

        return $input;
    }

    /**
     * Set form attributes
     * @param array $attributes
     * @return static
     */
    public function attributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Get form attribute
     * @param string $name
     * @return array|null
     */
    public function attribute(string $name = null, $value = null)
    {
        if (is_null($name)) {
            return $this->attributes;
        }

        if (!is_null($value)) {
            $this->attributes[$name] = $value;
        }

        return $this->attributes[$name] ?? null;
    }

    /**
     * Generate form header for model
     * @param Model $model
     * @return static
     */
    public function model(Model $model = null)
    {
        if ($model) {
            $this->model = $model;
        }

        \Form::setModel($this->model);

        return $this;
    }

    /**
     * Generating form group
     * @return string
     */
    public function generateGroups() : string
    {
        return Input::generateArray($this->inputs);
    }

    /**
     * Generate form head html
     * @return HtmlString
     */
    public function header()
    {
        return \Form::open($this->attributes);
    }

    /**
     * Generating form header with attributes
     * @return string
     */
    public function close()
    {
        return \Form::close();
    }

    /**
     * @return array
     */
    protected function viewVars() : array
    {
        return [
            'form' => $this,
        ];
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (!in_array($name, Input::$availableTypes)) {
            throw new MethodNotFoundException('Call to undefined method', static::class, $name, $arguments);
        }

        $arguments = array_prepend($arguments, $name);

        return call_user_func_array([$this, 'add'], $arguments);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->get();
    }
}