<?php

namespace App\Services\FormBuilder\Inputs;

use App\Services\FormBuilder\Inputs\Contracts\Input;

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
    public function input() : string
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