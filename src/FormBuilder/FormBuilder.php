<?php

namespace Savich\FormBuilder;

use Savich\FormBuilder\Inputs\ButtonInput;
use Savich\FormBuilder\Inputs\CheckboxInput;
use Savich\FormBuilder\Inputs\ColorInput;
use Savich\FormBuilder\Inputs\Contracts\Input;
use Savich\FormBuilder\Inputs\DateInput;
use Savich\FormBuilder\Inputs\DatetimeInput;
use Savich\FormBuilder\Inputs\DatetimeLocalInput;
use Savich\FormBuilder\Inputs\EmailInput;
use Savich\FormBuilder\Inputs\FileInput;
use Savich\FormBuilder\Inputs\HiddenInput;
use Savich\FormBuilder\Inputs\ImageInput;
use Savich\FormBuilder\Inputs\MonthInput;
use Savich\FormBuilder\Inputs\NumberInput;
use Savich\FormBuilder\Inputs\PasswordInput;
use Savich\FormBuilder\Inputs\RadioInput;
use Savich\FormBuilder\Inputs\RangeInput;
use Savich\FormBuilder\Inputs\ResetInput;
use Savich\FormBuilder\Inputs\SearchInput;
use Savich\FormBuilder\Inputs\SelectInput;
use Savich\FormBuilder\Inputs\SubmitInput;
use Savich\FormBuilder\Inputs\TelephoneInput;
use Savich\FormBuilder\Inputs\TextareaInput;
use Savich\FormBuilder\Inputs\TextInput;
use Savich\FormBuilder\Inputs\TimeInput;
use Savich\FormBuilder\Inputs\UrlInput;
use Savich\FormBuilder\Inputs\WeekInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

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
    public function button(string $name, array $options = []) : ButtonInput
    {
        return $this->add(ButtonInput::class, $name, null, $options);
    }

    /**
     * Add new checkbox input
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return CheckboxInput
     */
    public function checkbox(string $name, $value = null, array $options = []) : CheckboxInput
    {
        return $this->add(CheckboxInput::class, $name, $value, $options);
    }

    /**
     * Add new color input
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return ColorInput
     */
    public function color(string $name, $value = null, array $options = []) : ColorInput
    {
        return $this->add(ColorInput::class, $name, $value, $options);
    }

    /**
     * Add new date input
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return DateInput
     */
    public function date(string $name, $value = null, array $options = []) : DateInput
    {
        return $this->add(DateInput::class, $name, $value, $options);
    }

    /**
     * Add new datetime input
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return DatetimeInput
     */
    public function datetime(string $name, $value = null, array $options = []) : DatetimeInput
    {
        return $this->add(DatetimeInput::class, $name, $value, $options);
    }

    /**
     * Add new datetime-local input
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return DatetimeLocalInput
     */
    public function datetimeLocal(string $name, $value = null, array $options = []) : DatetimeLocalInput
    {
        return $this->add(DatetimeLocalInput::class, $name, $value, $options);
    }

    /**
     * Add new email input
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return EmailInput
     */
    public function email(string $name, $value = null, array $options = []) : EmailInput
    {
        return $this->add(EmailInput::class, $name, $value, $options);
    }

    /**
     * Add new file input
     * @param string $name
     * @param array $options
     * @return FileInput
     */
    public function file(string $name, array $options = []) : FileInput
    {
        return $this->add(FileInput::class, $name, null, $options);
    }

    /**
     * Add new hidden input
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return HiddenInput
     */
    public function hidden(string $name, $value = null, array $options = []) : HiddenInput
    {
        return $this->add(HiddenInput::class, $name, $value, $options);
    }

    /**
     * Add new image input
     * @param string $name
     * @param array $options
     * @return ImageInput
     */
    public function image(string $name, array $options = []) : ImageInput
    {
        return $this->add(ImageInput::class, $name, null, $options);
    }

    /**
     * Add new month input
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return MonthInput
     */
    public function month(string $name, $value = null, array $options = []) : MonthInput
    {
        return $this->add(MonthInput::class, $name, $value, $options);
    }

    /**
     * Add new number input
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return NumberInput
     */
    public function number(string $name, $value = null, array $options = []) : NumberInput
    {
        return $this->add(NumberInput::class, $name, $value, $options);
    }

    /**
     * Add new password input
     * @param string $name
     * @param array $options
     * @return PasswordInput
     */
    public function password($name, array $options = []) : PasswordInput
    {
        return $this->add(PasswordInput::class, $name, null, $options);
    }

    /**
     * Add new radio input
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return RadioInput
     */
    public function radio(string $name, $value = null, array $options = []) : RadioInput
    {
        return $this->add(RadioInput::class, $name, $value, $options);
    }

    /**
     * Add new range input
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return RangeInput
     */
    public function range(string $name, $value = null, array $options = []) : RangeInput
    {
        return $this->add(RangeInput::class, $name, $value, $options);
    }

    /**
     * Add new reset input
     * @param string $name
     * @param array $options
     * @return ResetInput
     */
    public function reset(string $name, array $options = []) : ResetInput
    {
        return $this->add(ResetInput::class, $name, null, $options);
    }

    /**
     * Add new search input
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return SearchInput
     */
    public function search(string $name, $value = null, array $options = []) : SearchInput
    {
        return $this->add(SearchInput::class, $name, $value, $options);
    }

    /**
     * Add new select input
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return SelectInput
     */
    public function select(string $name, $value = null, array $options = []) : SelectInput
    {
        return $this->add(SelectInput::class, $name, $value, $options);
    }

    /**
     * Add new submit input
     * @param string $name
     * @param array $options
     * @return SubmitInput
     */
    public function submit($name, array $options = []) : SubmitInput
    {
        $input = $this->add(SubmitInput::class, $name, null, $options);

        $input->model($this->model);

        return $input;
    }

    /**
     * Add new tel input
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return TelephoneInput
     */
    public function tel(string $name, $value = null, array $options = []) : TelephoneInput
    {
        return $this->add(TelephoneInput::class, $name, $value, $options);
    }

    /**
     * Add new textarea input
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return TextareaInput
     */
    public function textarea(string $name, $value = null, array $options = []) : TextareaInput
    {
        return $this->add(TextareaInput::class, $name, $value, $options);
    }

    /**
     * Add new text input
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return TextInput
     */
    public function text(string $name, $value = null, array $options = []) : TextInput
    {
        return $this->add(TextInput::class, $name, $value, $options);
    }

    /**
     * Add new time input
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return TimeInput
     */
    public function time(string $name, $value = null, array $options = []) : TimeInput
    {
        return $this->add(TimeInput::class, $name, $value, $options);
    }

    /**
     * Add new url input
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return UrlInput
     */
    public function url(string $name, $value = null, array $options = []) : UrlInput
    {
        return $this->add(UrlInput::class, $name, $value, $options);
    }

    /**
     * Add new week input
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return WeekInput
     */
    public function week(string $name, $value = null, array $options = []) : WeekInput
    {
        return $this->add(WeekInput::class, $name, $value, $options);
    }

    /**
     * Adding new input
     * @param string $inputNamespace
     * @param string $name
     * @param mixed $value
     * @param array $options
     * @return Input
     */
    public function add(string $inputNamespace, string $name, $value = null, array $options = []) : Input
    {
        $input = \FormInput::create($inputNamespace, $name, $value, $options);

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
     * @return string
     */
    public function __toString()
    {
        return $this->get();
    }
}