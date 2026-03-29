<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class QueueJob extends Model
{
    protected $table = 'jobs';

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'integer',
        'available_at' => 'integer',
        'reserved_at' => 'integer',
    ];

    protected $appends = [
        'created_at_date',
        'created_at_formatted',
    ];

    protected function createdAtDate(): Attribute
    {
        return Attribute::make(
            get: fn (): ?Carbon => isset($this->attributes['created_at'])
                ? Carbon::createFromTimestamp((int) $this->attributes['created_at'])
                : null,
        );
    }

    protected function createdAtFormatted(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => $this->created_at_date?->format('d-m-Y'),
        );
    }
}
