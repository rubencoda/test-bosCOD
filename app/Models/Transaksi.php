<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'nilai_transfer',
        'id_transaksi',
        'users_id',
        'bank_tujuan',
        'bank_pengirim',
        'bank_perantara',
        'atasnama_tujuan',
        'kode_unik',
        'biaya_admin',
        'total_transfer',
        'status',
        'masa_berlaku',
    ];

    public function getUser()
    {
        return $this->hasOne(User::class, 'users_id', 'id');
    }

    public function getBankTujuan()
    {
        return $this->hasOne(Bank::class, 'bank_tujuan', 'id');
    }

    public function getBankPengirim()
    {
        return $this->hasOne(Bank::class, 'bank_pengirim', 'id');
    }

    public function getBankPerantara()
    {
        return $this->hasOne(Bank::class, 'bank_perantara', 'id');
    }

    public static function generateIdTransaksi()
    {
        $prefix = 'TF';
        $date = Carbon::now()->format('ymd');
        $today = Carbon::now()->startOfDay();

        $count = self::where('created_at', '>=', $today)->count() + 1;
        $counter = str_pad($count, 5, '0', STR_PAD_LEFT);

        return $prefix . $date . $counter;
    }

    public static function generateUniqueCode()
    {
        do {
            $code = sprintf('%03d', mt_rand(0, 999));
        } while (Transaksi::where('kode_unik', $code)->exists());

        return $code;
    }

    public static function getOneDayFromNow()
    {
        $now = Carbon::now();
        $oneDayFromNow = $now->addDay();
        $formattedDate = $oneDayFromNow->format('Y-m-d\TH:i:sP');
        $date = preg_replace('/(:00)$/', '', $formattedDate);

        return $date;
    }

    public static function createTransaksi($data)
    {
        $create = self::create([
            'id_transaksi' => $data['id_transaksi'],
            'users_id' => $data['users_id'],
            'nilai_transfer' => $data['nilai_transfer'],
            'bank_tujuan' => $data['bank_tujuan'],
            'atasnama_tujuan' => $data['atasnama_tujuan'],
            'bank_pengirim' => $data['bank_tujuan'],
            'kode_unik' => $data['kode_unik'],
            'biaya_admin' => $data['biaya_admin'],
            'total_transfer' => $data['total_transfer'],
            'bank_perantara' => $data['bank_perantara'],
            'status' => $data['status'],
            'masa_berlaku' => $data['masa_berlaku'],
        ]);

        return $create;
    }
}
