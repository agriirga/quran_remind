<?php

namespace App\Services;

use App\User;
use App\QuranSurat;
use App\ArabicVerse;
use App\IndonesianVerse;
use Illuminate\Support\Facades\Mail;
use App\Mail\AyatSendMail;

class AyatServices
{
    public function randomUserAyat($user_id)
    {
        $user =  User::find($user_id)->first();
        //dd($user->email);
        $index = rand(1,6236);
        $this->getSpecificAyat($index, $user->email);
    }

    public function getSpecificAyat($index,$mail_to)
    {        
        $start = $index;
        $end = $index;

        $first_letter = '';
        $complete_ayat = null;
        
        // cari yang awalnya huruf kapital 
        while (true){
            $potongan_ayat = $this->getIndonesian($start);
            $first_letter = $potongan_ayat[0];
            // concat potongan ayat
            $complete_ayat = $potongan_ayat . $complete_ayat;
            // bergerak mundur untuk mencari awalan ayat
            
            //jika menemukan huruf kapital
            if (ctype_upper($first_letter))
                break;
            $start--;
        }

        $last_char = strlen($complete_ayat);

        // cari akhir ayat dengan karakter terakhir tanda baca titik
        while(true){
            $potongan_ayat = $this->getIndonesian($end);
            // gabunkan ayat dengan selanjutnya
            $complete_ayat = $complete_ayat . $potongan_ayat ;
            $last_char = strlen($complete_ayat);
            // jika sudah menekan titik, sudah merupakan akhir ayat
            if ($complete_ayat[$last_char - 1] == '.')
                break;

            $end++;
        }

        $arabic_ayat = $this->getRangeArabic($start,$end);
        $surat = IndonesianVerse::select('surah_id')->where('id', $index)->first();
        //dd($surat_id);
        $quran_surat = QuranSurat::select('id','nama_surat')->where('id', $surat->surah_id)->first();
        $first_ayat = IndonesianVerse::select('ayah_no')->where('id',$start)->first();
        $last_ayat = IndonesianVerse::select('ayah_no')->where('id',$end)->first();
        //dd($start,$end,$quran_surat,$first_ayat, $complete_ayat,$last_ayat);
        
        if ($start != $end)
            $ayat_surat = 'QS : ' . $quran_surat->nama_surat . '[' . $quran_surat->id .']' . ' , ayat:' . $first_ayat->ayah_no . '-' . $last_ayat->ayah_no;
        else
            $ayat_surat = 'QS : ' . $quran_surat->nama_surat . '[' . $quran_surat->id .']' . ' , ayat:' . $first_ayat->ayah_no;

        $indonesian_ayat = $complete_ayat;

        Mail::to($mail_to)->send(new AyatSendMail($indonesian_ayat,$ayat_surat, $arabic_ayat));
        //return view('ayat.show')->with(compact('indonesian_ayat','ayat_surat','arabic_ayat'));
    }

    public function getIndonesian($ayat){
        $ayat = IndonesianVerse::where('id',$ayat)->first();
        $potongan_ayat = $ayat->verse;
        return $potongan_ayat;
    }

    public function getRangeArabic($start,$end){ 
        $ayats = ArabicVerse::select('verse')->whereBetween('id',[$start,$end])->get();
        return $ayats;
    }

    public function getRangeIndonesian($start,$end){ 
        $ayats = IndonesianVerse::select('verse')->whereBetween('id',[$start,$end])->get();
        return $ayats;
    } 
}