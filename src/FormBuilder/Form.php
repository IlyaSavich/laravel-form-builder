<?php

namespace Savich\FormBuilder;

use Illuminate\Container\Container;
use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\HtmlString;
use Illuminate\View\View;
use Prophecy\Exception\Doubler\MethodNotFoundException;
use Savich\FormBuilder\Inputs\Contracts\Input;
use Savich\FormBuilder\Inputs\SubmitInput;

/**
 * @method Factory|View create(Model $model = null)
 * @method Factory|View edit(Model $model = null)
 */
abstract class Form
{
    /**
     * @var string
     */
    protected $formId;

    /**
     * @var FormBuilder
     */
    protected $builder;

    /**
     * Request map. Mapping on request type
     * @var array
     */
    protected $requests = [];

    /**
     * @var Request|FormRequest
     */
    protected $request;

    /**
     * @var Model|\Eloquent
     */
    protected $model;

    /**
     * Available types for get() method
     * @var array
     */
    protected $types = ['create', 'edit'];

    /**
     * Type for current form generation
     * @var string
     */
    protected $type = 'create';

    /**
     * Vars that passed into form outside
     * @var array
     */
    protected $vars = [];

    /**
     * Depends on type property mapping form route
     * @var array
     */
    protected $routes = [];

    /**
     * Form method mapping on form type
     * @var array
     */
    protected $methods = ['create' => 'POST', 'edit' => 'PUT'];

    public function __construct(FormBuilder $builder)
    {
        $this->builder = $builder;
        $this->formId = snake_case(static::class);
    }

    /**
     * Method implements logic for building form
     * @param FormBuilder $builder
     */
    abstract protected function make(FormBuilder $builder): void;

    /**
     * Generating form
     * @param Model $model
     */
    public function build(Model $model = null): void
    {
        $this->model = $model;

        $this->builder->model($this->model);
        $this->make($this->builder);
        $this->formAttributes($this->builder);
    }

    /**
     * Get resolved request
     * @return FormRequest|Request
     */
    public function request(): Request
    {
        return $this->request;
    }

    /**
     * Set type
     * @param string $type
     * @return static
     */
    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set form vars
     * @param array $vars
     * @param mixed $value
     * @return static
     */
    public function vars($vars, $value = null): self
    {
        if (is_array($vars)) {
            $this->vars = array_merge($this->vars, $vars);

        } elseif (is_string($vars)) {
            if (!$value) {
                return $this->vars[$vars] ?? null;
            }

            $this->vars[$vars] = $value;
        }

        return $this;
    }

    /**
     * Get route for type
     * @param array $parameters
     * @return string
     */
    public function route(array $parameters = []): string
    {
        $route = $this->routes[$this->type] ?? null;

        if (!is_null($route) && !empty($parameters)) {
            $route = [$route, $parameters];
        }

        return $route;
    }

    /**
     * Get method for type
     * @return string|null
     */
    public function method(): ?string
    {
        return $this->methods[$this->type] ?? null;
    }

    /**
     * Get model or it property
     * @param Model|string $property
     * @return Model|mixed
     */
    public function model($property = null)
    {
        if (!$property) {
            return $this->model;
        }

        if ($property instanceof Model) {
            $this->model = $property;

            return $this->model;
        }

        return $this->model ? $this->model->$property : null;
    }

    /**
     * Initialize the form request with data from the given request.
     * @param Request $current
     * @param Container $app
     */
    public function initializeRequest(Request $current, Container $app)
    {
        $requestMethod = $current->getMethod();
        /* @var Request|FormRequest $form */
        $this->request = $app->make($this->requests[$requestMethod] ?? Request::class);

        $files = $current->files->all();

        $files = is_array($files) ? array_filter($files) : $files;

        $this->request->initialize(
            $current->query->all(), $current->request->all(), $current->attributes->all(),
            $current->cookies->all(), $files, $current->server->all(), $current->getContent()
        );

        $this->request->setJson($current->json());

        if ($session = $current->getSession()) {
            /* @var Session $session */
            $this->request->setLaravelSession($session);
        }

        $this->request->setUserResolver($current->getUserResolver());

        $this->request->setRouteResolver($current->getRouteResolver());

        if (is_a($this->request, FormRequest::class)) {
            $this->request->setContainer($app)->setRedirector($app->make(Redirector::class));
        }
    }

    /**
     * Get open form tag
     *
     * @return HtmlString
     */
    public function open(): HtmlString
    {
        return $this->builder->header();
    }

    /**
     * Get close form tag
     *
     * @return HtmlString
     */
    public function close(): HtmlString
    {
        return $this->builder->close();
    }

    /**
     * Get submit input
     *
     * @param string $value
     * @param array $options
     * @return Inputs\Contracts\Input
     * @throws \Exception
     */
    protected function submit(string $value, array $options = []): Input
    {
        $options['form'] = $options['form'] ?? $this->formId;

        return $this->builder->add(SubmitInput::class, 'submit', $value, $options);
    }

    /**
     * Apply some changes for form attributes. Check routes
     * @param FormBuilder $builder
     * @return static
     */
    protected function formAttributes(FormBuilder $builder): self
    {
        $builder->attribute('id', $builder->attribute('id') ?: $this->formId);

        return $this->checkRoutes($builder)
            ->checkMethod($builder);
    }

    /**
     * Check route. Set route
     * @param FormBuilder $builder
     * @return static
     */
    protected function checkRoutes(FormBuilder $builder): self
    {
        $route = $builder->attribute('route');

        if (!$route) {
            $builder->attribute('route', $this->route($this->model ? $this->model('id') : null));
        }

        return $this;
    }

    /**
     * Check route. Set route
     * @param FormBuilder $builder
     * @return static
     */
    protected function checkMethod(FormBuilder $builder): self
    {
        $method = $builder->attribute('method');

        if (!$method) {
            $builder->attribute('method', $this->method());
        }

        return $this;
    }

    /**
     * Get input from builder by it's name
     *
     * @param string $inputName
     *
     * @return Input
     */
    public function input(string $inputName): Input
    {
        return $this->builder->get($inputName);
    }

    /**
     * Check that called method exists in available get() method types.
     * When true overwrite default type by called method name and call standard get method
     *
     * @param string $name
     * @param array $arguments
     */
    public function __call($name, $arguments)
    {
        if (!in_array($name, $this->types)) {
            throw new MethodNotFoundException('Method not found in class ', static::class, $name, $arguments);
        }

        $this->type($name);
        call_user_func_array([$this, 'build'], $arguments);
    }

    /**
     * Get input from builder by it's name as property
     *
     * @param string $name
     * @return Input
     */
    public function __get($name): Input
    {
        return $this->input($name);
    }

    public function __toString(): string
    {
        return (string) $this->builder->build();
    }
}
