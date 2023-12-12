<?php

namespace Laltu\LaravelUi;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Laltu\LaravelUi\Skeleton\SkeletonClass
 */
class LaravelUiFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-ui';
    }
}
