<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderRoom;
use App\Models\Room;
use Illuminate\Support\Str;
use Validator;

class OrderRoomController extends Controller
{
    /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\Response
      */
    public function index(Request $request)
    {
        if (isset($_GET['filter'])) {
            $data = OrderRoom::where('name', 'LIKE', '%'.$_GET['filter'].'%')->where('user_id',$request->user()->id)->paginate(10);

            return response()->json(['statusCode'=>200,'message'=>'Data Order Room has been obtained.','data'=>$data], 200);
        } else {
            $data = OrderRoom::where('user_id',$request->user()->id)->paginate(10);

            return response()->json(['statusCode'=>200,'message'=>'Data Order Room has been obtained.','data'=>$data], 200);
        }
    }


    public function booking(Request $request)
    {

        $diff = strtotime($request->start_from) - strtotime($request->end_at);
        $diff = (int)abs(round($diff / 86400));
        $total = $diff * Room::where('id', $request->room_id)->first()->price;
        $kode_transaksi = 'ISHT-ROOM'.Str::upper(Str::random(6));

        $validator = Validator::make($request->all(), [

            'start_from' => 'required',
            'end_at' => 'required',
            'room_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['statusCode'=>401,'message'=>'You got an error while validating the form.','errors'=>$validator->errors()], 401);
        }

        $order = OrderRoom::create([
            'user_id' => $request->user()->id,
            'order_code' => $kode_transaksi,
            'start_from' => $request->start_from,
            'end_at' => $request->end_at,
            'room_id' => $request->room_id,
            'total' => $total,
        ]);

        return response()->json(['statusCode'=>200,'message'=>'Order success.'], 200);
    }

    public function pay_room(Request $request,$id)
    {
        // $validator = Validator::make($request->all(), [
        //     'bukti' => 'required',
        // ]);
        // if ($validator->fails()) {
        //     return response()->json(['statusCode'=>401,'message'=>'You got an error while validating the form.','errors'=>$validator->errors()], 401);
        // }

        OrderRoom::where('id',$id)->update(['status'=>3,'bukti'=>$request->bukti]);
        $data = OrderRoom::paginate(10);
        return response()->json(['statusCode'=>200,'message'=>'Order pay.','data'=>$data], 200);
    }

    public function cancel_room(Request $request,$id)
    {
        OrderRoom::where('id',$id)->update(['status'=>1]);
        $data = OrderRoom::paginate(10);
        return response()->json(['statusCode'=>200,'message'=>'Order pay.','data'=>$data], 200);
    }


    public function createInvoice(Request $req) {
        $service = new Service();
        return $service->createInvoice($req->all());
    }
}
