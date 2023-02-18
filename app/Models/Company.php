<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Company extends Model
{
    use HasFactory;

    protected $appends = ['coins'];

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    protected function coins(): Attribute
    {
        return new Attribute(
            get: fn () => $this->wallet->coins,
        );
    }

}
