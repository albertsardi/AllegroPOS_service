<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailReport extends Mailable
{
    use Queueable, SerializesModels;
    
    public function __construct()
    {
    }

    public function build()
    {
        //return dd(base_path());
        $cpm_path = "C://xampp2//htdocs//CPM";
        //$cpm_path = "http://localhost:84/sso";
        return $this->from('albertsardi@gmail.com')
                    ->view('test-email')
                    ->with( ['nama' => 'Albert Sardi2',
                            'website' => 'www.albertsardi2.com',
                    ])
                    //->attach(base_path().'/public/ConsolidateReport.pdf', [
                    ->attach($cpm_path.'/public/tmp/test-report.pdf', [
                        'as' => 'ConsolidateReport.pdf',
                        'mime' => 'application/pdf',
                        ]);
    }
}