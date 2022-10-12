<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderRoom;
use App\Models\Room;
use Illuminate\Support\Str;

class OrderRoomController extends Controller
{
    /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\Response
      */
    public function index()
    {
        if (isset($_GET['filter'])) {
            $data = OrderRoom::where('name', 'LIKE', '%'.$_GET['filter'].'%')->paginate(10);

            return response()->json(['statusCode'=>200,'message'=>'Data Order Room has been obtained.','data'=>$data], 200);
        } else {
            $data = OrderRoom::paginate(10);

            return response()->json(['statusCode'=>200,'message'=>'Data Order Room has been obtained.','data'=>$data], 200);
        }
    }


    public function booking(Request $request)
    {

        $diff = strtotime($request['start_from']) - strtotime($request['end_at']);
        $diff = (int)abs(round($diff / 86400));
        $total = $diff * Room::where('id', $request->room_id)->first()->price;
        $kode_transaksi = 'HT-STRCD'.Str::upper(Str::random(6));


        $order = OrderRoom::create([
            'user_id' => 1,
            'order_code' => $kode_transaksi,
            'start_from' => $request->start_from,
            'end_at' => $request->end_at,
            'room_id' => $request->room_id,
            'total' => $total + 2000,
        ]);

        return response()->json(['statusCode'=>200,'message'=>'Order success.'], 200);
    }

    public function pay_room(Request $request,$id)
    {
        OrderRoom::where('id',$id)->update(['status'=>3]);
        $data = OrderRoom::paginate(10);
        return response()->json(['statusCode'=>200,'message'=>'Order pay.','data'=>$data], 200);
    }

    public function cancel_room(Request $request,$id)
    {
        OrderRoom::where('id',$id)->update(['status'=>1]);
        $data = OrderRoom::paginate(10);
        return response()->json(['statusCode'=>200,'message'=>'Order pay.','data'=>$data], 200);
    }
}
