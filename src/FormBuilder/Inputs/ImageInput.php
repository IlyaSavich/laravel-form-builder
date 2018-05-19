<?php

namespace Savich\FormBuilder\Inputs;

use Savich\FormBuilder\Inputs\Contracts\Input;

class ImageInput extends Input
{
    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string
    {
        return 'image';
    }

    /**
     * @return string
     */
    public function input()
    {
        return \Form::image($this->url(), $this->name, $this->attributes);
    }

    /**
     * Get url for image submit
     * @return string|null
     */
    protected function url()
    {
        return $this->attributes['url'] ?? null;
    }
}