<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'biaya_admin'
    ];

    public function getRekening()
    {
        return $this->hasOne(Rekening::class);
    }

    public static function getBank($name)
    {
        return self::where('name', 'like', "%{$name}%")->first();
    }
}
