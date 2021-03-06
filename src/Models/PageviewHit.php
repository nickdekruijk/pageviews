<?php

namespace NickDeKruijk\Pageviews\Models;

use Illuminate\Database\Eloquent\Model;

class PageviewHit extends Model
{
    protected $casts = [
        'time' => 'datetime',
        'parsed' => 'boolean',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('pageviews.database_prefix', 'pageviews_') . 'hits');
    }

    public function scopeUnparsed($query)
    {
        $query->where('parsed', false);
    }

    public function scopeFrom($query, $from)
    {
        if ($from) {
            $query->whereDate('time', '>=', $from);
        }
    }

    public function scopeTo($query, $to)
    {
        if ($to) {
            $query->whereDate('time', '<=', $to);
        }
    }

    public function getHostAttribute($value)
    {
        if ($value) {
            return $value;
        }
        $url = parse_url($this->referer);
        return $url['host'] ?? null;
    }
}
