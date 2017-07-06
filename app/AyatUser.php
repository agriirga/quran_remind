<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AyatUser extends Model
{
    protected $fillable = ['ayat_id','user_id'];

    public function ayat()
    {
        return $this->belongsTo('App\QuranSurat');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
