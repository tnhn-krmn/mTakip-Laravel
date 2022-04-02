<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demand extends Model
{
    use HasFactory;
    const  CLOSED = 1;
    const  OPEN = 0;


    protected $fillable = ['userId', 'title', 'text'];
    protected $appends = ['date'];

    public function customer()
    {
        return $this->hasOne(User::class,'id','userId');
    }

    public function getStatusTextAttribute()
    {
        switch ($this->attributes['status']) 
        {
            case self::CLOSED:
                return "Kapalı";
                break;
            
            default:
               return "Açık";
                break;
        }
    }
    static function timeAgo($gelen_zaman)
    {
        $gelen_zaman =  strtotime($gelen_zaman);
        $zaman_farki = time() - $gelen_zaman;
        $saniye = $zaman_farki;
        $dakika = round($zaman_farki / 60);
        $saat = round($zaman_farki / 3600);
        $gun = round($zaman_farki / 86400);
        $hafta = round($zaman_farki / 604800);
        $ay = round($zaman_farki / 2419200);
        $yil = round($zaman_farki / 29030400);
        if ($saniye < 60) {
            if ($saniye == 0) {
                return "az önce";
            } else {
                return $saniye . ' saniye önce';
            }
        } else if ($dakika < 60) {
            return $dakika . ' dakika önce';
        } else if ($saat < 24) {
            return $saat . ' saat önce';
        } else if ($gun < 7) {
            return $gun . ' gün önce';
        } else if ($hafta < 4) {
            return $hafta . ' hafta önce';
        } else if ($ay < 12) {
            return $ay . ' ay önce';
        } else {
            return $yil . ' yıl önce';
        }
    }

    public function getDateAttribute()
    {
        return self::timeAgo($this->attributes['created_at']);
    }
}
