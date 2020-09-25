<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ShipmentCredential;
use App\Providers\RouteServiceProvider;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function sendLoginResponse(Request $request)
    {

        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        //Логирование на АПИ shipments
        $client = new Client();
        $headers = [ 'Content-Type' => 'application/json' ];
        $body =  [
            'email' => config('shipment.api_email'),
            'password' => config('shipment.api_password')
        ];

        $shipmentRequest = new \GuzzleHttp\Psr7\Request('POST', config('shipment.api_url').'login', $headers, json_encode($body));
        $res = $client->send($shipmentRequest);

        $result = json_decode($res->getBody()->getContents());
        $token = $result->data[0]->token;
        $clientShipment = ShipmentCredential::where('email', config('shipment.api_email'))->first();
        $clientShipment->token = $token;
        $clientShipment->save();

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }
}
