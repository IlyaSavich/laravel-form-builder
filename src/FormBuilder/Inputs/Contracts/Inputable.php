<?php

namespace Savich\FormBuilder\Inputs\Contracts;

use Illuminate\Contracts\View\View;

interface Inputable
{
    /**
     * Generating input
     * @return View
     */
    public function generate();

    /**
     * Specify inputs type there
     * @return string
     */
    public function type() : string;

    /**
     * @return string
     */
    public function input() : string;
}