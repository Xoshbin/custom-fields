<?php

namespace Xoshbin\CustomFields\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Xoshbin\CustomFields\CustomFields
 */
class CustomFields extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Xoshbin\CustomFields\CustomFields::class;
    }
}
