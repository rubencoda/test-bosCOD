<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_id',
        'no_rekening',
    ];

    public function getBank()
    {
        return $this->hasOne(Bank::class, 'bank_id', 'id');
    }
}
