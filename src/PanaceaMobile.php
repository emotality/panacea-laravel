<?php

namespace Emotality\Panacea;

use Illuminate\Support\Facades\Facade;

class PanaceaMobile extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Emotality\Panacea\PanaceaMobileFacade::class;
    }
}
