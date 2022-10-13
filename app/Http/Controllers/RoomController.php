<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Validator;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (isset($_GET['filter'])) {
            $data = Room::where('name', 'LIKE', '%'.$_GET['filter'].'%')->paginate(10);
            $data->getCollection()->transform(function ($value) {
                $datas = [];
                $datas['id'] = $value->id;
                $datas['name'] = $value->name;
                $datas['price'] = $value->price;
                $datas['faccility'] = $value->faccility;
                $datas['thumb'] = env('APP_URL').'/thumbRoom/'.$value->thumb;
                $datas['rate'] = $value->rate;
                $datas['description'] = $value->description;
                return $datas;
            });
            return response()->json(['statusCode'=>200,'message'=>'Data Room has been obtained.','data'=>$data], 200);
        } else {
            $data = Room::where('hotel_id', $_GET['id'])->paginate(10);
            $data->getCollection()->transform(function ($value) {
                $datas = [];
                $datas['id'] = $value->id;
                $datas['name'] = $value->name;
                $datas['price'] = $value->price;
                $datas['faccility'] = $value->faccility;
                $datas['thumb'] = env('APP_URL').'/thumbRoom/'.$value->thumb;
                $datas['rate'] = $value->rate;
                $datas['description'] = $value->description;
                return $datas;
            });

            return response()->json(['statusCode'=>200,'message'=>'Data Room has been obtained.','data'=>$data], 200);
        }
    }

    public function search(Request $request)
    {

        $solve = [];
        $data = Room::where(['hotel_id'=>$request->hotel_id])->get();
        foreach ($data as $key => $value) {
            $value->thumb = env('APP_URL').'/thumbRoom/'.$value->thumb;
            $facc = json_decode(json_decode($value->faccility));
            if (count(array_intersect($facc, $request->faccility)) == count($request->faccility)) {
                $solve[] = $value;
            }
        }
        return response()->json(['statusCode'=>200,'message'=>'Data Search has been obtained.','data'=>$solve], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null(Order::where('id', $request->order_id)->first())) {
            return response()->json(['statusCode'=>401,'message'=>'Data tidak dRoomukan.'], 401);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'qty' => 'required',
            'order_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['statusCode'=>401,'message'=>'You got an error while validating the form.','errors'=>$validator->errors()], 401);
        }
        Room::create([
            'name'=>$request->name,
            'price'=>$request->price,
            'qty'=>$request->qty,
            'order_id'=>$request->order_id,
        ]);
        return response()->json(['statusCode'=>200,'message'=>'Data Room has been created.'], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rooms  $plant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (is_null(Order::where('id', $request->order_id)->first())) {
            return response()->json(['statusCode'=>401,'message'=>'Data tidak dRoomukan.'], 401);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'qty' => 'required',
            'order_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['statusCode'=>401,'message'=>'You got an error while validating the form.','errors'=>$validator->errors()], 401);
        }
        Room::where('id', $id)->update([
            'name'=>$request->name,
            'price'=>$request->price,
            'qty'=>$request->qty,
            'order_id'=>$request->order_id,
        ]);
        return response()->json(['statusCode'=>200,'message'=>'Data Room has been updated.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rooms  $plant
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Room::where('id', $id)->delete();
        return response()->json(['statusCode'=>200,'message'=>'Data Room has been deleted.'], 200);
    }
}
