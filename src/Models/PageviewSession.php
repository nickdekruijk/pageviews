<?php

namespace NickDeKruijk\Pageviews\Models;

use Illuminate\Database\Eloquent\Model;

class PageviewSession extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('pageviews.database_prefix', 'pageviews_') . 'sessions');
    }

    public function scopeUnparsed($query)
    {
        $query->where('parsed', false);
    }
}
