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
 * @property string $outerView
 * @property array $outerAttributes
 * @property array $before
 * @property array $after
 * @property bool $onlyInner
 * @property string $innerView
 * @property Model $model
 */
abstract class Input implements Inputable
{
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
     * Path to outer view. This view defines rendering input wrapped with
     *
     * @var string
     */
    protected $outerView;

    /**
     * @var array
     */
    protected $outerAttributes;

    /**
     * Will be rendered only inner part if true
     *
     * @var bool
     */
    protected $onlyInner = false;

    /**
     * Path to inner view
     *
     * @var string
     */
    protected $innerView;

    /**
     * Model for this input
     *
     * @var Model
     */
    protected $model;

    /**
     * @param string $name
     * @param mixed $value
     * @param array $attributes
     * @throws \Exception
     */
    public function __construct(string $name, $value = null, array $attributes = [])
    {
        $this->name = $name;
        $this->value = $value;
        $this->attributes = array_merge($this->defaultAttributes(), $attributes);
        $this->outerView = config('form-builder.views.input.outer');
        $this->innerView = config('form-builder.views.input.inner');
        $this->outerAttributes = $this->defaultOuterAttributes();

        $this->addErrorClass();
    }

    /**
     * Generate array of inputs
     *
     * @param array $inputs
     * @return string
     */
    public static function generateArray(array $inputs): string
    {
        return array_reduce($inputs, function (string $html, Input $input) {
            return $html . $input->build();
        }, '');
    }

    /**
     * Render outer view or inner if only inner flag was settled
     *
     * @return View
     */
    public function build(): View
    {
        return view($this->onlyInner ? $this->innerView : $this->outerView)->with($this->viewVars());
    }

    /**
     * Render inner view
     *
     * @return View
     */
    public function inner(): View
    {
        return view($this->innerView)->with($this->viewVars());
    }

    /**
     * Add array of attributes, merge them with defined ones before
     *
     * @param array $attributes
     * @return static
     */
    public function attributes(array $attributes): self
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    /**
     * Set specific attribute
     *
     * @param string $name
     * @param string $value
     * @return static
     */
    public function attribute(string $name, string $value): self
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * Set view template
     *
     * @param string $view
     * @return static
     */
    public function view(string $view): self
    {
        $this->outerView = $view;

        return $this;
    }

    /**
     * Set view for inner part
     *
     * @param string $view
     * @return static
     */
    public function internalView(string $view): self
    {
        $this->innerView = $view;

        return $this;
    }

    /**
     * Set attributes for outer block
     *
     * @param array $attributes
     * @return static
     */
    public function outerAttributes(array $attributes): self
    {
        $this->outerAttributes = array_merge($this->outerAttributes, $attributes);

        return $this;
    }

    /**
     * Set label for input
     *
     * @param string|null $text
     * @param array $options
     * @param bool $escape_html
     * @return static
     */
    public function label($text = null, array $options = [], bool $escape_html = true): self
    {
        $this->label = \Form::label($this->name, $text, $options, $escape_html);

        return $this;
    }

    /**
     * Set model for input
     *
     * @param Model $model
     * @return static
     */
    public function model(Model $model = null): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Mark for input to render only inner part
     *
     * @return static
     */
    public function onlyInner(): self
    {
        $this->onlyInner = true;

        return $this;
    }

    /**
     * Array of attributes to string for html
     *
     * @return string
     */
    public function generateOuterAttributes(): string
    {
        $attributes = [];

        foreach ($this->outerAttributes as $key => $value) {
            $attributes[] = "$key=\"$value\"";
        }

        return implode(' ', $attributes);
    }

    /**
     * Add error class to outer attributes
     */
    protected function addErrorClass(): void
    {
        if (!optional(session()->get('errors'))->has($this->name)) {
            return;
        }

        $this->outerAttributes['class'] .= ' ' . config('form-builder.html.error.class');
    }

    /**
     * Specify vars that will pass to view
     *
     * @return array
     */
    protected function viewVars(): array
    {
        return ['input' => $this];
    }

    /**
     * Array of default group attributes
     *
     * @return array
     */
    protected function defaultOuterAttributes(): array
    {
        return ['class' => 'form-group'];
    }

    /**
     * Array of default attributes for input
     *
     * @return array
     */
    protected function defaultAttributes(): array
    {
        return ['class' => 'form-control'];
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

    /**
     * Add ability generate view on string cast
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->build();
    }
}
