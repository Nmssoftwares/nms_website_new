<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsEvent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'visitor_id', 'type', 'path', 'label', 'section', 'kind', 'value',
        'device', 'browser', 'os', 'referrer',
    ];
}