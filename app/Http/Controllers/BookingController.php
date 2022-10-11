<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Order,Kamar,User};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;

use App\Services\Midtrans\CreateSnapTokenService;


class BookingController extends Controller
{
    public function order(Request $request)
    {

        $data = Kamar::where('tipe_id',$request->tipe_id)->where('max','>=',$request->adult + $request->childreen)->get();
        return view('users.order', [
            'data' => $data,
            'all' =>$request->all(),
        ]);
    }

    public function checkout(Request $request)
    {
        $req = json_decode($request->req,TRUE);
        $diff = strtotime($req['check_in']) - strtotime($req['check_out']);
        $diff = (int)abs(round($diff / 86400));
        $total = $diff * Kamar::where('id',$request->room)->first()->harga;
        $kode_transaksi = 'HT-STRCD'.Str::upper(Str::random(6));


        $order = Order::create([
            'user_id' => Auth::user()->id,
            'order_code' => $kode_transaksi,
            'check_in' => $req['check_in'],
            'check_out' => $req['check_out'],
            'total_harga' => $total + 2000,
            'kamar_id' => $request->room,
            'status' => 0,
        ]);


        return redirect()->route('transaction');

    }


    public function updateTransaction(Request $request)
    {

        if($request['status'] == 'Success'){
            Order::where('id',$request['id'])->update([
                'payment_status' => $request['result']['status_code'] . ' | '.$request['result']['status_message'],
                'payment_method' => $request['result']['bank'].' | '.$request['result']['payment_type'],
                'transaction_id' => $request['result']['transaction_id'],
                'transaction_time' => $request['result']['transaction_time'],
                'guest_name' => $request['guests'],
                'status' => 1,
            ]);
        }else {
            Order::where('id',$request['id'])->update([
                'payment_status' => $request['result']['status_code'] . ' | '.$request['result']['status_message'],
                'payment_method' => $request['result']['bank'].' | '.$request['result']['payment_type'],
                'transaction_id' => $request['result']['transaction_id'],
                'transaction_time' => $request['result']['transaction_time'],
                'guest_name' => $request['guests'],
                'status' => -2,
            ]);
        }


        return response()->json(['statusCode'=>200,'message'=>'Success Update Transaction !','status'=>'success','code' => Order::where('id',$request['id'])->first()->order_code], 200);
    }


    public function transaction(Request $request)
    {
        return view('users.transaction');
    }

    public function pay(Request $request,$code)
    {

        $order = Order::where('order_code',$code)->first();

        $snapToken = $order->snap_token;
        if (empty($snapToken)) {
            $midtrans = new CreateSnapTokenService($order);
            $snapToken = $midtrans->getSnapToken();
            $order->snap_token = $snapToken;
            $order->save();
        }
        $data = $order;
        return view('users.checkout', compact('order', 'snapToken','data'));


    }


    public function checkoutNonRegister(Request $request)
    {

        $data = json_decode($request->data,TRUE);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        $data['user_id'] = Auth::id();
        $id = Order::insertGetId($data);
        return redirect()->route('invoice',['id' => $id]);

    }

    public function invoice($code)
    {
        $data = Order::where('order_code',$code)->first();
        return view('users.invoice', compact('data'));
    }

    public function transactionSuccess()
    {
        return view('users.transaction-success');
    }

    public function transactionError()
    {
        return view('users.transaction-error');
    }
    public function transactionPending()
    {
        return view('users.transaction-pending');
    }

    public function transactionInvoice($code)
    {
        $data = Order::where('order_code',$code)->first();
        return view('users.transaction-invoice', compact('data'));
    }

    public function cancelOrder(Request $request,$id)
    {
        Order::where('id',$id)->update([
            'status' => -1,
        ]);
        return redirect()->back()->with('message','Order Canceled !');
    }

}
