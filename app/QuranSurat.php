<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuranSurat extends Model
{
    protected $fillable = ['nama_surat','jumlah_ayat'];

    public function indonesian_verse()
    {
        return $this->belongsTo('App\IndonesianVerse');
    }
}
