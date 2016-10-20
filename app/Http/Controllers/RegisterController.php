<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\RegisterFormRequest;
use Illuminate\Support\Facades\Mail;
use Redirect;
use View;
 

class RegisterController extends Controller {

  public function create()
    {
        return view('frontend.register');
    }

  public function store(RegisterFormRequest $request)
  {
      $clientname    = $request->input('client_name');
      $contactnumber = $request->input('contact_number');
      $address       = $request->input('address');
      $email         = $request->input('email');
      $date          = $request->input('date');
      $eventdate     = $request->input('event_date');
      $eventvenue    = $request->input('event_venue');
      $eventtype     = $request->input('event_type');
      $package       = $request->input('package_selected');
      $guests        = $request->input('number_of_guests');
      $modepayement  = $request->input('mode_payement');
      $advance       = $request->input('advance');
      $balance       = $request->input('balance');
      $suggestions   = $request->input('suggestions');

      $this->clientname    = ucwords($clientname);
      $this->contactnumber = $contactnumber;
      $this->address       = $address;
      $this->email         = $email;
      $this->date          = $date;
      $this->eventdate     = $eventdate;
      $this->eventvenue    = $eventvenue;
      $this->eventtype     = $eventtype;
      $this->package       = $package;
      $this->guests        = $guests;
      $this->modepayement  = $modepayement;
      $this->advance       = $advance;
      $this->balance       = $balance;
      $this->suggestions   = $suggestions;

      Mail::send('email.registermail', ['clientname' => $clientname, 'contactnumber' => $contactnumber, 'address' => $address,
          'email' => $email], function ($message)
      {
          $message->from($this->email, 'Party Crooks - ' . $this->clientname);
          $message->to('chippymerinmathew05@gmail.com');
         
      });


      return redirect('Register')
          ->withFlashMessage('Thanks for your registration. We will contact you soon.')
          ->withType('success');

  }

}