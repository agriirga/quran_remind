<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\QuranSurat;
use App\ArabicVerse;
use App\IndonesianVerse;
use App\Mail\AyatSendMail;
use Illuminate\Support\Facades\Mail;

class QuranController extends Controller
{
    public function specificAyat($surat,$ayat){
        $index_ayat = IndonesianVerse::select('id')->where('surah_id',$surat)->where('ayah_no',$ayat)->first();
        return $this->getSpecificAyat($index_ayat->id);
    }

    public function getSpecificAyat($index)
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

        Mail::to('agriie07@gmail.com')->send(new AyatSendMail($indonesian_ayat,$ayat_surat, $arabic_ayat));
        return view('ayat.show')->with(compact('indonesian_ayat','ayat_surat','arabic_ayat'));
    }

    public function randomAyat()
    {
        $random_index  = rand(1,6236);       
        return $this->getSpecificAyat($random_index);
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
    
    public function fullSurat($id){
        $quran_surat = QuranSurat::select('id','nama_surat','jumlah_ayat')->where('id',$id)->first();
        $jumlah_ayat = $quran_surat->jumlah_ayat;
        
        $first_ayat =  IndonesianVerse::select('id')->where ('surah_id',$id)->where('ayah_no', 1)->first();
        $last_ayat =  IndonesianVerse::select('id')->where ('surah_id',$id)->where('ayah_no', $jumlah_ayat)->first();

        $indonesian_verse= $this->getRangeIndonesian($first_ayat->id,$last_ayat->id);
        $indonesian_ayat = null;

        for ($i = 1; $i<= $jumlah_ayat-1 ; $i++)
        {   
            $ayat = $indonesian_verse->pluck('verse')->get($i);
            $indonesian_ayat = $indonesian_ayat . $ayat;
        }

        $arabic_ayat = $this->getRangeArabic($first_ayat->id,$last_ayat->id);
        $ayat_surat = 'QS : ' . $quran_surat->nama_surat . '[' . $quran_surat->id .']' . ' , ayat: 1 -'   . $quran_surat->jumlah_ayat;

        return view('ayat.show')->with(compact('indonesian_ayat','ayat_surat','arabic_ayat'));
    }
}
