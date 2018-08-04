<?php

namespace Savich\FormBuilder;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Savich\FormBuilder\Inputs\Contracts\Input;

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
    public function build()
    {
        return view($this->view)->with($this->viewVars());
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
     * Adding new input. Input should has unique name otherwise exception will rise
     *
     * @param string $inputNamespace
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return Input
     * @throws \Exception
     */
    public function add(string $inputNamespace, string $name, $value = null, array $options = []) : Input
    {
        if (!is_subclass_of($inputNamespace, Input::class)) {
            throw new \Exception($inputNamespace . ' must be inheritor of ' . Input::class);
        }

        if (isset($this->inputs[$name])) {
            throw new \InvalidArgumentException("Input with name [$name] is already defined");
        }

        $input = new $inputNamespace($name, $value, $options);
        $this->inputs[$name] = $input;

        return $input;
    }

    /**
     * Get specific input by it name
     *
     * @param string $name
     * @return null|Input
     */
    public function get(string $name): ?Input
    {
        return $this->inputs[$name] ?? null;
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
     * @param string $value
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
    public function generateInputs() : string
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
     * @return HtmlString
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
        return ['form' => $this];
    }

    /**
     * Proxy to another method. Get specific input by it name
     *
     * @param $name
     * @return null|Input
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->build();
    }
}
