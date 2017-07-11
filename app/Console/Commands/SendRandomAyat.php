<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\QuranController;
use App\Services\AyatServices;

class SendRandomAyat extends Command
{
    private $ayat_svc;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail_ayat:random';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending Random Ayat via Email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(AyatServices $ayat_svc)
    {
        $this->ayat_svc = $ayat_svc;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::where('email','<>','')->get();
        //dd($users);
        foreach($users as $user){
            //app('App\Http\Controllers\QuranController')->randomAyat($user->id);
            //dd($user->id);
            $this->ayat_svc->randomUserAyat($user->id);
            $this->info('The email had been sent');
        }
    }
}
