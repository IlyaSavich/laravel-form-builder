<?php

namespace Savich\FormBuilder;

use Illuminate\Container\Container;
use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Prophecy\Exception\Doubler\MethodNotFoundException;
use Symfony\Component\Debug\Exception\FatalThrowableError;

/**
 * Class Form
 * @package App\Services\FormBuilder
 * @method Factory|View create(Model $model = null)
 * @method Factory|View edit(Model $model = null)
 */
abstract class Form
{
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

    public function __construct(FormBuilder $builder = null)
    {
        $this->builder = $builder ?? app(FormBuilder::class);
    }

    /**
     * Method implements logic for building form
     * @param FormBuilder $builder
     */
    abstract protected function make(FormBuilder $builder);

    /**
     * Generating form
     * @param Model $model
     * @return Factory|View
     */
    public function build(Model $model = null)
    {
        $this->model = $model;

        $this->builder->model($this->model);
        $this->make($this->builder);
        $this->formAttributes($this->builder);

        return $this->builder->get();
    }

    /**
     * Get resolved request
     * @return FormRequest|Request
     */
    public function request()
    {
        return $this->request;
    }

    /**
     * Set type
     * @param string $type
     * @return string|static
     */
    public function type(string $type)
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
    public function vars($vars, $value = null)
    {
        if (is_array($vars)) {
            $this->vars = array_merge($this->vars, $vars);

        } elseif (is_string($vars)) {
            $this->vars[$vars] = $value;
        }

        return $this;
    }

    /**
     * Get route for type
     * @param array|mixed $parameters
     * @return string
     */
    public function route($parameters = null)
    {
        $route = $this->routes[$this->type] ?? null;

        if (!is_null($parameters)) {
            $route = [$route, $parameters];
        }

        return $route;
    }

    /**
     * Get method for type
     * @return string
     */
    public function method()
    {
        return $this->methods[$this->type] ?? null;
    }

    /**
     * Get model or it property
     * @param string $property
     * @return Model|mixed
     */
    public function model($property = null)
    {
        if (!$this->model) {
            return null;
        }

        if (!$property) {
            return $this->model;
        }

        return $this->model->$property;
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
     * Apply some changes for form attributes. Check routes
     * @param FormBuilder $builder
     * @return Form
     */
    protected function formAttributes(FormBuilder $builder)
    {
        return $this->checkRoutes($builder)
            ->checkMethod($builder);
    }

    /**
     * Check route. Set route
     * @param FormBuilder $builder
     * @return static
     */
    protected function checkRoutes(FormBuilder $builder)
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
    protected function checkMethod(FormBuilder $builder)
    {
        $method = $builder->attribute('method');

        if (!$method) {
            $builder->attribute('method', $this->method());
        }

        return $this;
    }

    /**
     * Check that called method exists in available get() method types.
     * When true overwrite default type by called method name and call standard get method
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (in_array($name, $this->types)) {
            $this->type($name);

            return call_user_func_array([$this, 'build'], $arguments);
        }

        throw new MethodNotFoundException('Method not found in class ', static::class, $name, $arguments);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;

        } elseif (array_key_exists($name, $this->vars)) {
            return $this->vars[$name];
        }

        return null;
    }

    /**
     * @param string $name
     * @param $value
     * @throws FatalThrowableError
     */
    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            throw new FatalThrowableError(new \Exception('Cannot access protected property ' . static::class . '::$' . $name));
        }

        $this->vars[$name] = $value;
    }
}