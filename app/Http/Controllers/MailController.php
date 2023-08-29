<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Mail\MalasngodingEmail;
use Illuminate\Support\Facades\Mail;
 
class MailController extends Controller
{
	public function sendmail(){
 
		return 'send-email';
		$sendto='albertsardi@gmail.com';
		Mail::to($sendto)->send(new MalasngodingEmail());
 
		return "Email telah dikirim";
 
	}
 
}