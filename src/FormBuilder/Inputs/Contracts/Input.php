<?php

namespace Savich\FormBuilder\Inputs\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;

/**
 * Class Input
 * @package App\Services\FormBuilder\Inputs\Contracts
 *
 * @property string $name
 * @property mixed $value
 * @property array $attributes
 * @property string $label
 * @property string $view
 * @property string $internalGroup
 * @property string $defaultView
 * @property array $groupAttributes
 * @property array $before
 * @property array $after
 * @property bool $withoutGroup
 * @property string $withoutGroupView
 * @property Model $model
 */
abstract class Input implements Inputable
{
    public static $availableTypes = [
        'button',
        'checkbox',
        'color',
        'date',
        'datetime',
        'datetime-local',
        'email',
        'file',
        'hidden',
        'image',
        'month',
        'number',
        'password',
        'radio',
        'range',
        'reset',
        'search',
        'select',
        'submit',
        'tel',
        'textarea',
        'text',
        'time',
        'url',
        'week',
    ];

    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var string
     */
    protected $label;

    /**
     * Path to view group
     * @var string
     */
    protected $view;

    /**
     * Path to view inside group
     * @var string
     */
    protected $internalGroup;

    /**
     * Path to view. This view will be rendered when view property will not specified
     * @var string
     */
    protected $defaultView;

    /**
     * @var array
     */
    protected $groupAttributes;

    /**
     * Inputs that must be prepended
     * @var array
     */
    protected $before = [];

    /**
     * Inputs that must be appended
     * @var array
     */
    protected $after = [];

    /**
     * Input will be no wrapped when true
     * @var bool
     */
    protected $withoutGroup = false;

    /**
     * Path to view when render without group wrapper
     * @var string
     */
    protected $withoutGroupView;

    /**
     * Model for this input
     * @var Model
     */
    protected $model;

    /**
     * Input constructor.
     * @param string $name
     * @param mixed $value
     * @param array $attributes
     * @throws \Exception
     */
    public function __construct(string $name, $value = null, $attributes = [])
    {
        if (!$this->validType()) {
            throw new \Exception('Type `' . $this->type() . '` is not supported');
        }

        $this->name = $name;
        $this->value = $value;
        $this->attributes = $attributes;
        $this->defaultView = config('form-builder.views.input');
        $this->withoutGroupView = config('form-builder.views.without-group-input');
    }

    /**
     * Generate array of inputs
     * @param array $inputs
     * @return mixed
     */
    public static function generateArray(array $inputs)
    {
        return array_reduce($inputs, function ($html, $input) {

            if (!is_a($input, self::class)) {
                $instanceType = is_object($input) ? get_class($input) : gettype($input);
                throw new \Exception('Expected instance of ' . self::class . '. Got ' . $instanceType);
            }
            /** @var Input $input */
            return $html . $input->generate();
        });
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function generate()
    {
        return view($this->viewPath())->with($this->viewVars());
    }

    /**
     * Set input attributes
     * @param array $attributes
     * @return static|Input
     */
    public function attributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Set view template
     * @param string $view
     * @return static
     */
    public function view(string $view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Set internal group
     * @param string $view
     * @return static
     */
    public function internalView(string $view)
    {
        $this->withoutGroupView = $view;

        return $this;
    }

    /**
     * Set attributes for group
     * @param array $attributes
     * @return static
     */
    public function groupAttributes(array $attributes)
    {
        $this->groupAttributes = $attributes;

        return $this;
    }

    /**
     * Set label for input
     * @param string|null $text
     * @param array $options
     * @param bool $escape_html
     * @return static
     */
    public function label($text = null, array $options = [], bool $escape_html = true)
    {
        $this->label = \Form::label($this->name, $text, $options, $escape_html);

        return $this;
    }

    /**
     * Add before input another one
     * @param string $type
     * @param string $name
     * @param callable $callback
     * @return $this
     */
    public function before(string $type, string $name, callable $callback = null)
    {
        $input = \FormInput::create($type, $name);

        if ($callback) {
            $callback($input, $this);
        }

        $this->before[] = $input;

        return $this;
    }

    /**
     * Add after input another one
     * @param string $type
     * @param string $name
     * @param callable $callback
     * @return $this
     */
    public function after(string $type, string $name, callable $callback = null)
    {
        $input = \FormInput::create($type, $name);

        if ($callback) {
            $callback($input, $this);
        }

        $this->after[] = $input;

        return $this;
    }

    /**
     * Set model for input
     * @param Model $model
     * @return static
     */
    public function model(Model $model = null)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Set without group property. The input will be no wrapped when true
     * @return static
     */
    public function withoutGroup()
    {
        $this->withoutGroup = true;

        return $this;
    }

    /**
     * Generating inputs before
     * @return string
     */
    public function generateBefore()
    {
        return static::generateArray($this->before);
    }

    /**
     * Generating inputs before
     * @return string
     */
    public function generateAfter()
    {
        return static::generateArray($this->after);
    }

    /**
     * Array of attributes to string for html
     * @return string
     */
    public function generateGroupAttributes()
    {
        $attributes = [];

        foreach ($this->groupAttributes ?? $this->defaultGroupAttributes() as $key => $value) {
            $attributes[] = "$key=\"$value\"";
        }

        return implode(' ', $attributes);
    }

    /**
     * Get path to view that should be rendered
     * @return string
     */
    protected function viewPath()
    {
        return $this->withoutGroup ?
            $this->withoutGroupView :
            $this->view ?? $this->defaultView;
    }

    /**
     * Specify vars that will pass to view
     * @return array
     */
    protected function viewVars()
    {
        return [
            'input' => $this,
        ];
    }

    /**
     * Array of default group attributes
     * @return array
     */
    protected function defaultGroupAttributes()
    {
        return [
            'class' => 'form-group',
        ];
    }

    /**
     * Check that specified type is supported
     * @return bool
     */
    protected function validType() : bool
    {
        return in_array($this->type(), static::$availableTypes);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        return null;
    }
}