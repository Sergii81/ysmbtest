<?php

namespace App\Http\Controllers;

use App\Models\ShipmentCredential;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function index()
    {
        $shipmentCredentials = ShipmentCredential::where('email', config('shipment.api_email'))->first();

        $token = $shipmentCredentials->token;

        $data = $this->api($token, 'GET', 'shipment');
        $shipments = $data->shipments;

        return view('home', [
            'shipments' => $shipments,
            'token'    => $token
        ]);
    }

    /**
     * Переход на страницу создания Sipment с проверкой наличия существующих позиций
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function pageShipmentCreate(Request $request)
    {
        if($request->token) {
            $data = $this->api($request->token, 'GET', 'shipment');
            if(!empty($data->shipments)) {
                $last = Arr::last($data->shipments);
                $lastId = $last->id;
            } else {
                $lastId = 0;
            }
        }
        $id = $lastId + 1;
        $shipmentName = 'shipment_'.$id;

        return view('shipment.create', [
            'id' => $id,
            'shipmentName' => $shipmentName
        ]);
    }

    /**
     * Создание Shipment
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function pageShipmentCreatePost(Request $request)
    {
        $shipmentCredentials = ShipmentCredential::where('email', config('shipment.api_email'))->first();
        $token = $shipmentCredentials->token;
        $body = [
            'id' => $request->id,
            'name' => $request->name
        ];
        $this->api($token, 'POST', 'shipment', $body);

        return redirect()->route('home');
    }

    /**
     * Переход на страницу создания Item с проверкой наличия существующих позиций
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function pageItemCreate(Request $request)
    {
        $shipmentCredentials = ShipmentCredential::where('email', config('shipment.api_email'))->first();
        $token = $shipmentCredentials->token;
        $shipment = $this->api($token, 'GET', 'shipment/'.$request->id);
        if(!empty($shipment->items)) {
            $last = Arr::last($shipment->items);
            $lastId = $last->id;
        } else {
            $lastId = 0;
        }

        $id = $lastId + 1;

        return view('items.create', [
            'id' => $id,
            'shipment' => $shipment,
            'token' => $token
        ]);
    }

    /**
     * Создание Item
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function pageItemCreatePost(Request $request)
    {
        $token = $request->token;
        foreach($request->items['id'] as $itemKey => $itemValue){
            $body = [
                'id' => $request->items['id'][$itemKey],
                'shipment_id' => $request->items['shipment_id'][$itemKey],
                'name' => $request->items['name'][$itemKey],
                'code' => $request->items['code'][$itemKey],
            ];

            $this->api($token, 'POST', 'item', $body);
        }
        return redirect()->route('home');
    }

    /**
     * Переход на страницу удаления Item
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function pageItemDelete(Request $request)
    {

        $shipmentCredentials = ShipmentCredential::where('email', config('shipment.api_email'))->first();
        $token = $shipmentCredentials->token;
        $shipment = $this->api($token, 'GET', 'shipment/'.$request->id);

        return view('items.delete', [
            'shipment' => $shipment,
            'token' => $token
        ]);
    }

    /**
     * Удаление Item
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function pageItemDeletePost(Request $request)
    {
        $token = $request->token;
        if(!empty($request->delete)) {
            foreach ($request->delete as $itemID => $value) {
                $this->api($token, 'DELETE', 'item/'.$itemID);
            }
        }
        return redirect()->route('page_delete_item', $request->shipment_id);
    }




    /**
     * Метод для отправка запросов по АПИ
     * @param $token
     * @param $method
     * @param $url
     * @param null $body
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function api ($token, $method, $url, $body=null)
    {
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$token,
        ];

        $request = new \GuzzleHttp\Psr7\Request($method, config('shipment.api_url').$url, $headers, json_encode($body));
        $res = $client->send($request);

        $result = json_decode($res->getBody()->getContents());

        if(isset($result->data)) {
            return $result->data;
        }
        return $result;
    }



}
