<?php

use Illuminate\Database\Seeder;
use App\QuranSurat;
use App\IndonesianVerse;
use App\ArabicVerse;

class QuranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        // run surah
        $rows = Excel::selectSheetsByIndex(0)->load('/public/seeder_file/surah.xlsx', function($reader){
            //options
        })->get();
        //dd($rows);

        $rowRules = [
            'no' => 'required',
            'nama' => 'required',
            'ayat' => 'required',
        ];

        $i= 0;
        foreach($rows as $row)
        {
            $validator = Validator::make($row->toArray(), $rowRules);
            
            if($validator->fails())
            {
                continue;
            }

       
            try{
                $surah = new QuranSurat();
                $surah->nama_surat = $row['nama'];
                $surah->jumlah_ayat = $row['ayat'];
                $surah->save();                
            } 

            catch(Exception $e){
                 //ada yg sn nya double dd()
                //dd($row['nama'], $e);
                continue;
            }
            $i++;   
        }    
    
    // run verse
    $rows = Excel::selectSheetsByIndex(0)->load('/public/seeder_file/indonesia.xlsx', function($reader){
            //options
        })->get();
        //dd($rows);

        $rowRules = [
            'surah' => 'required',
            'verse' => 'required',
            'indonesian' => 'required',
        ];


        $i= 0;
        foreach($rows as $row)
        {
            $validator = Validator::make($row->toArray(), $rowRules);
            
            if($validator->fails())
            {
                continue;
            }
    
            try{
                $ayat = new IndonesianVerse();
                $ayat->surah_id = $row['surah'];
                $ayat->ayah_no = $row['verse'];
                $ayat->verse = $row['indonesian'];
                $ayat->save();                
            } 

            catch(Exception $e){
                 //ada yg sn nya double dd()
                dd($row['surah'], $row['verse']);
                continue;
            }
            $i++;   
        }
        
    // run arabic verse
    $rows = Excel::selectSheetsByIndex(0)->load('/public/seeder_file/arabic.xlsx', function($reader){
            //options
        })->get();
        //dd($rows);

        $rowRules = [
            'surah' => 'required',
            'verse' => 'required',
            'arab' => 'required',
        ];

        $i= 0;
        foreach($rows as $row)
        {
            $validator = Validator::make($row->toArray(), $rowRules);
            
            if($validator->fails())
            {
                continue;
            }

            try{
                $ayat = new ArabicVerse();
                $ayat->surah_id = $row['surah'];
                $ayat->ayah_no = $row['verse'];
                $ayat->verse = $row['arab'];
                $ayat->save();                
            } 

            catch(Exception $e){
                dd($row['surah'], $row['verse']);
                continue;
            }
            $i++;   
        }

    }
}
