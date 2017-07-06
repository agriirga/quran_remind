<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QuranSurat;
use App\ArabicVerse;
use App\IndonesianVerse;

class RandomAyatController extends Controller
{
    public function randomize()
    {
        $random_ayat =  0 ;
        $first_letter = '1';

        while ($random_ayat == 0 || ! ctype_upper($first_letter) )
        {
            // random ayat quran
            $random_ayat = rand(1,6236);
            $potongan_ayat = $this->getAyat($random_ayat);
            $first_letter = $potongan_ayat[0];
        }

        $first_random_ayat = $random_ayat;
        $start = $first_random_ayat;
        $complete_ayat = null;
        
        while($complete_ayat == null || $complete_ayat[$last_char - 1] != '.'){
            $potongan_ayat = $this->getAyat($random_ayat++);
            $complete_ayat = $complete_ayat . $potongan_ayat ;
            $last_char = strlen($complete_ayat);
        }
        
        $last_random_ayat = $random_ayat;
        $end = $last_random_ayat;
        $continous_ayat= 0;

        
        while ($first_random_ayat != $last_random_ayat){
            /*$user_random_ayat = new RandomAyatUser ();
            $user_random_ayat->user_id = $user->id;
            $user_random_ayat->ayat_id = $first_random_ayat;
            $user_random_ayat->save();
            */
            $first_random_ayat++;
            $continous_ayat++;
        }
        

        $final_ayat = IndonesianVerse::with('surah')
            ->where('id', $first_random_ayat)->first();
        $last_ayat = $final_ayat->ayah_no;
        $first_ayat = $last_ayat - $continous_ayat;
        
        $arabic_ayat = $this->getArabic($start,$end);
        //dd($arabic_ayat);
        $surat = QuranSurat::select('id','nama_surat')->where('id',$final_ayat->surah_id)->first();
        $ayat_surat = 'QS : ' . $surat->nama_surat . '[' . $surat->id .']' . ' , ayat:' . $first_ayat . '-' . $last_ayat;
        return view('ayat.index')->with(compact('complete_ayat','ayat_surat','arabic_ayat'));
    }

    public function getAyat($ayat){
        $ayat = IndonesianVerse::where('id',$ayat)->first();
        $potongan_ayat = $ayat->verse;

        return $potongan_ayat;
    }

    public function getArabic($start,$end){ 
        //dd($start,$end);
        
        $ayats = ArabicVerse::select('verse')->whereBetween('id',[$start,$end])->get();
        //dd($ayats);
        return $ayats;
    }
}
