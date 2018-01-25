<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Contracts\Validation\Validator;
use Mail;

class ContactUsController extends Controller
{
    function postForm(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required|max:50',
            'message' => 'required',
        ]);

        if($validator->fails()) {
            \Session::put('error_message', 'Invalid message data!');
            return redirect()->route('home');
        }
        $name = $request->get('name');
        $email = $request->get('email');
        $subject = $request->get('subject');
        $message = $request->get('message');
        $m = $message;
        Mail::send('emails.contact', ['name' => $name, 'email' => $email, 'subject' => $subject, 'message' => $message, 'm' => $m ],
            function ($m) use ($name, $email, $subject) {
            $m->from('tdp-slack@rainbowriders.dk', null);
            $m->to('info@rainbowriders.dk', null);
            $m->replyTo($email);
            $m->subject($subject);
        });

        \Session::put('success_message', 'Your message has been sent!');
        return redirect()->route('home');
    }
}
