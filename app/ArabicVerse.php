<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArabicVerse extends Model
{
    protected $fillable = ['surah_id','ayah_no','verse'];
    
    public function surah()
    {
        return $this->belongsTo('App\QuranSurat');
    }
}
