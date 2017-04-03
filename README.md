# Laravel Form Builder

The form builder service for Laravel

# Installation

The package requires `laravelcollective/html` package.
So first of all pass the installation steps for it.

### Require

```
composer require ilyasavich/form-builder
```

### Register Provider and Facade

```
in app.php config

'providers' => [
    // ...
    Savich\FormBuilder\FormServiceProvider::class,
],

'aliases' => [
    // ...
    'FormInput' => Savich\FormBuilder\Inputs\Factory\FormInputFacade::class,
]
```

# Usage

To create new form first of all extending base `Form` class and overwrite method `make`.
This method implements form building logic

```
use Savich\FormBuilder\Form;
use Savich\FormBuilder\FormBuilder;

class UserForm extends Form
{
    protected function make(FormBuilder $builder): FormBuilder
    {
    }
}
```

### Create inputs

The package provide to create all available html inputs.
To add new input in your form class you need call `add` method of builder instance.
It signature
```
public function add(string $inputNamespace, string $name, $value = null, array $options = []) : Input
```
To create simple input write ...

```
use Savich\FormBuilder\Form;
use Savich\FormBuilder\FormBuilder;
use Savich\FormBuilder\Inputs\EmailInput;

class UserForm extends Form
{
    protected function make(FormBuilder $builder): FormBuilder
    {
        $builder->add(EmailInput::class, 'email');
    }
}
```

## Customization

### Attributes

Here are several methods to customize input

```
// you can set input attributes in different ways
// also you can specify default input value

$value = 'Hello World!';
$attributes = ['class' => 'form-control'];
$builder->add(TextInput::class, 'input_name', $value)->attributes($attributes);
$builder->add(TextInput::class, 'input_name', $value, $attributes);
```

### Labels

```
$builder->add(TextInput::class, 'input_name')->label('My Label');
```

### Group customization

By default inputs will generating in such format

```
$builder->add(TextInput::class, 'input_name');

// generated view

<div class="form-group">
    <input type="text" name="input_name">
</div>
```

If you don't need to wrap input by group
```
$builder->add(TextInput::class, 'input_name')->withoutGroup();

// generated view

<input type="text" name="input_name">
```

You can customize group attributes

```
$builder->add(TextInput::class, 'input_name')->groupAttributes(['class' => 'my-class', 'inputID']);

// generated view

<div class="my-class" id="inputID">
    <input type="text" name="input_name">
</div>
```

### Overwrite views

If you need specific input generating you can overwrite default view by your custom

```
$builder->add(TextInput::class, 'input_name')->view('path.to.view');
```

In case where you need to overwrite view without group you can ...

```
//this will overwrite code inside form-group div
$builder->add(TextInput::class, 'input_name')->internalView('path.to.view');
```

When you need to overwrite only group you can ...
In view you have `$input` object that is an instance of your input class.
```
$builder->add(TextInput::class, 'input_name')->view('path.to.view');

// in resources.views.path.to.view
// in withoutGroupView there path to internal view, you can specify custom or there will be default
<div {!! $input->generateGroupAttributes() !!}>
    // write custom stuff ...
    @include($input->withoutGroupView)
</div>
```

### Available input properties

The you can find list of available properties of `$input` object

| Name               | Description
|:-------------------|:------------
| `$name`            | Input name
| `$value`           | Input value
| `$attributes`      | Input attributes
| `$label`           | Input label in html
| `$view`            | Path to input group view. Will be `null` if you don't set it in `view()` method
| `$defaultView`     | Path to default group view. You can set it in config file
| `$groupAttributes` | Array of group attributes
| `$before`          | Array of inputs that must be inserted inside group before current
| `$after`           | Array of inputs that must be inserted inside group after current
| `$withoutGroup`    | Can be set by call `withoutGroup()` method. Indicates that need generate input without wrapping group
| `$withoutGroupView`| Path to view inside group
| `$model`           | Model that will be binding for input

### Create simple form

For example you need to create simple login form with at least two inputs.
Ok, it will something like that

```
use Savich\FormBuilder\Form;
use Savich\FormBuilder\FormBuilder;

class LoginForm extends Form
{
    protected function make(FormBuilder $builder)
    {
        $builder->email('email')->label('Email');
        $builder->password('password')->label('Password');
        
        $builder->submit('Save');
    }
}

```

After that you need to add this form in controller action

```
class LoginController extends Controller
{
    public function showLoginForm(LoginForm $form)
    {
        $formHtml = $form->get();
        
        return view('login')->with('form', $formHtml);
    }
}
```

And, finally, render form in view file

```
in resources/views/login.blade.php

{!! $form !!}
```

Lets create action for submitting login form.
In our controller

```
class LoginController extends Controller
{
    // ...
    
    public function login(LoginForm $form)
    {
        $form->request(); // access to request
        
        // login logic
    }
}
```