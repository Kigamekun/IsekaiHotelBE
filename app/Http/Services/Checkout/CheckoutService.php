<?php

namespace App\Http\Services\Checkout;

use Xendit\Xendit;
use Illuminate\Support\Facades\Http;
use App\Models\OrderRoom;

class CheckoutService {

    function __construct() {
        Xendit::setApiKey('xnd_development_L2FycwwFbjD4ownXkHUkJO4kB8jio3GXQv6aKAnMTYmgEupiP4DWQPPRL9ttnAjF');
    }
    public function createInvoice($args,$room_id) {
        $date = new \DateTime();
        $redirectUrl = env('APP_URL').'/getInvoice/'.$room_id;
        $defParams = [
            'external_id' => 'ISEKAIHOTEL-' . $date->getTimestamp(),
            'payer_email' => 'invoice+demo@xendit.co',
            'description' => 'Checkout Kiga',
            'failure_redirect_url' => $redirectUrl,
            'success_redirect_url' => $redirectUrl
        ];
        $data = json_decode(json_encode($args), true);
        $defParams['failure_redirect_url'] = $redirectUrl;
        $defParams['success_redirect_url'] = $redirectUrl;
        $params = array_merge($defParams, $data);
        $response = [];
        try {
            $response = \Xendit\Invoice::create($params);
        } catch (\Throwable $e) {
            $response['message'] = $e->getMessage();
        }


        OrderRoom::where('id',$room_id)->update(['invoice_id'=>$response['id']]);
        logger($response);
        return $response;
    }


}
