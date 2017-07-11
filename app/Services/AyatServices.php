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
    public function randomUserAyat($user_id,  $is_need_view)
    {
        $user =  User::find($user_id)->first();
        $index = rand(1,6236);
        return $this->getSpecificAyat($index, $user->email, $is_need_view);
    }

    public function getSpecificAyat($index,$mail_to,$is_need_view)
    {        
        //dd($is_need_view);
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
        $quran_surat = QuranSurat::select('id','nama_surat')->where('id', $surat->surah_id)->first();
        $first_ayat = IndonesianVerse::select('ayah_no')->where('id',$start)->first();
        $last_ayat = IndonesianVerse::select('ayah_no')->where('id',$end)->first();
       
        if ($start != $end)
            $ayat_surat = 'QS : ' . $quran_surat->nama_surat . '[' . $quran_surat->id .']' . ' , ayat:' . $first_ayat->ayah_no . '-' . $last_ayat->ayah_no;
        else
            $ayat_surat = 'QS : ' . $quran_surat->nama_surat . '[' . $quran_surat->id .']' . ' , ayat:' . $first_ayat->ayah_no;

        $indonesian_ayat = $complete_ayat;
        
        try{
            Mail::to($mail_to)->send(new AyatSendMail($indonesian_ayat,$ayat_surat, $arabic_ayat));
        } catch(Exception $e){
            console.log('error', $e);
        }
        
        if ($is_need_view){
            return view('ayat.show')->with(compact('arabic_ayat','ayat_surat','indonesian_ayat'));
        }
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