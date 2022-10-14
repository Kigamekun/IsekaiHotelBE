<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderFood;
use App\Models\Food;
use Illuminate\Support\Str;
use Validator;

class OrderFoodController extends Controller
{
    /**
          * Display a listing of the resource.
          *
          * @return \Illuminate\Http\Response
          */
    public function index(Request $request)
    {
        if (isset($_GET['filter'])) {
            $data = OrderFood::where('name', 'LIKE', '%'.$_GET['filter'].'%')->where('user_id',$request->user()->id)->paginate(10);
            return response()->json(['statusCode'=>200,'message'=>'Data Order Room has been obtained.','data'=>$data], 200);
        } else {
            $data = OrderFood::where('user_id',$request->user()->id)->paginate(10);
            return response()->json(['statusCode'=>200,'message'=>'Data Order Room has been obtained.','data'=>$data], 200);
        }
    }


    public function order(Request $request)
    {

        $total = Food::where('id', $request->food_id)->first()->price & $request->qty;
        $kode_transaksi = 'ISHT-FOOD'.Str::upper(Str::random(6));

        $validator = Validator::make($request->all(), [
            'qty' => 'required',
            'address' => 'required',
            'food_id' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['statusCode'=>401,'message'=>'You got an error while validating the form.','errors'=>$validator->errors()], 401);
        }

        $order = OrderFood::create([
            'user_id' => $request->user()->id,
            'order_code' => $kode_transaksi,
            'qty'=>$request->qty,
            'address' => $request->address,
            'food_id' => $request->food_id,
            'total' => $total,
        ]);

        return response()->json(['statusCode'=>200,'message'=>'Order success.'], 200);
    }

    public function pay_food(Request $request, $id)
    {
        OrderFood::where('id', $id)->update(['status'=>3]);
        $data = OrderFood::where('user_id',$request->user()->id)->paginate(10);
        return response()->json(['statusCode'=>200,'message'=>'Order pay.','data'=>$data], 200);
    }

    public function cancel_food(Request $request, $id)
    {
        OrderFood::where('id', $id)->update(['status'=>1]);
        $data = OrderFood::where('user_id',$request->user()->id)->paginate(10);
        return response()->json(['statusCode'=>200,'message'=>'Order pay.','data'=>$data], 200);
    }
}
