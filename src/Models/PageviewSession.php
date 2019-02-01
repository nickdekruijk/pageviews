<?php

namespace NickDeKruijk\Pageviews\Models;

use Illuminate\Database\Eloquent\Model;

class PageviewSession extends Model
{
    protected $casts = [
        'time' => 'datetime',
        'parsed' => 'boolean',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('pageviews.database_prefix', 'pageviews_') . 'sessions');
    }

    public function scopeUnparsed($query)
    {
        $query->where('parsed', false);
    }

    public function scopeFrom($query, $from)
    {
        if ($from) {
            $query->where('from', '=>', $from);
        }
    }

    public function scopeTo($query, $to)
    {
        if ($to) {
            $query->where('to', '=<', $to);
        }
    }
}
