<?php

namespace NickDeKruijk\Pageviews;

use Illuminate\Support\Facades\Facade;

/**
 * @see \NickDeKruijk\Pageviews\Skeleton\SkeletonClass
 */
class PageviewsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pageviews';
    }
}
