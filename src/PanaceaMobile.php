<?php

namespace Emotality\Panacea;

use Illuminate\Support\Facades\Facade;

class PanaceaMobile extends Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return \Emotality\Panacea\PanaceaMobileAPI::class;
    }
}
