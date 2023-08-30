<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use \koolreport\inputs\Bindable;
use \koolreport\inputs\POSTBinding;
// use Anouar\Fpdf\Fpdf as baseFpdf;
use Codedge\Fpdf\Fpdf\Fpdf as baseFpdf;
use \Koolreport\widgets\koolphp\Table;
use \Koolreport\inputs\Select2;
use App\Mail\MyMail;
use App\Mail\EmailReport;

class MailController extends MainController
{
	//https://www.itsolutionstuff.com/post/how-to-send-email-with-attachment-in-laravelexample.html
	function mailsend()
	{
		//  return 'mailsend';
		//$return = $message->getReturnPath();
        //$sender = $message->getSender();
        //$from = $message->getFrom();

		//send mail
        Mail::to("albertsardi@gmail.com")->send(new EmailReport());
		return "Email telah dikirim";
	}


        //dd('Mail sent successfully');

		/*
		$detail = [
			'title' => 'title',
			'body' => 'this is body'
		];
		
		\Mail::to('albertsardi@yahoo.com')->send(new \App\Mail($detail));

		dd('Email is sent');
		
	}
*/
	


	
	
}

?>