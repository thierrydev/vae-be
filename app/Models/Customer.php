<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Traits\Dumpable;


class Customer extends Model
{
    use HasFactory;
    use Dumpable;
    use SoftDeletes;


    protected $fillable = [
        'name',
        'type',
        'email',
        'address',
        'city',
        'state',
        'postal_code',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
