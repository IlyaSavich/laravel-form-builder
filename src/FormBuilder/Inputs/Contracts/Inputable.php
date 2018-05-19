<?php

namespace Savich\FormBuilder\Inputs\Contracts;

use Illuminate\View\View;

interface Inputable
{
    /**
     * Generating input
     * @return View
     */
    public function build(): View;

    /**
     * Specify inputs type there
     * @return string
     */
    public function type(): string;

    /**
     * @return string
     */
    public function input();
}