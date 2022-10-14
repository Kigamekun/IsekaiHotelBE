<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Xendit\Xendit;
use App\Http\Services\Checkout\CheckoutService as Service;
use Illuminate\Support\Facades\Http;
use App\Models\OrderRoom;


class CheckoutController extends BaseController {

    public function index() {
        return View::make("v1/checkout/checkout");
    }


    public function onSubmit() {
        return View::make("v1/checkout/try-checkout");
    }


    public function create(Request $req) {
        $service = new Service();
        return $service->createInvoice($req->all(),$req->room_id);
    }
    public function getInvoice(Request $request,$id_room)
    {

        $order = OrderRoom::where('id',$id_room)->first();
        $response = Http::withHeaders([
            'Authorization' => 'Basic '.base64_encode('xnd_development_L2FycwwFbjD4ownXkHUkJO4kB8jio3GXQv6aKAnMTYmgEupiP4DWQPPRL9ttnAjF:'),
        ])->get('https://api.xendit.co/v2/invoices/'.$order->invoice_id);
        if ($response->json()['status'] == 'PAID') {
            OrderRoom::where('id',$id_room)->update(['status'=>3]);
        } else if ($response->json()['status'] == 'PENDING') {
            OrderRoom::where('id',$id_room)->update(['status'=>2]);
        } else {
            OrderRoom::where('id',$id_room)->update(['status'=>1]);
        }
        return redirect('http://localhost:3000/transaction');
    }
}
