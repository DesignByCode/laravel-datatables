<?php

namespace Designbycode\Datatables\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Designbycode\Datatables\Datatables
 */
class Datatables extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Designbycode\Datatables\Datatables::class;
    }
}
