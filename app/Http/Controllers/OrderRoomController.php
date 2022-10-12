<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            $data = Food::where('name', 'LIKE', '%'.$_GET['filter'].'%')->paginate(10);
            $data->getCollection()->transform(function ($value) {
                $datas = [];
                $datas['id'] = $value->id;
                $datas['name'] = $value->name;
                $datas['price'] = $value->price;
                $datas['faccility'] = $value->faccility;
                $datas['thumb'] = env('APP_URL').'/thumbFood/'.$value->thumb;
                $datas['rate'] = $value->rate;


                return $datas;
            });
            return response()->json(['statusCode'=>200,'message'=>'Data Food has been obtained.','data'=>$data], 200);
        } else {
            $data = Food::paginate(10);
            $data->getCollection()->transform(function ($value) {
                $datas = [];
                $datas['id'] = $value->id;
                $datas['name'] = $value->name;
                $datas['price'] = $value->price;
                $datas['faccility'] = $value->faccility;
                $datas['thumb'] = env('APP_URL').'/thumbFood/'.$value->thumb;
                $datas['rate'] = $value->rate;


                return $datas;
            });
            return response()->json(['statusCode'=>200,'message'=>'Data Food has been obtained.','data'=>$data], 200);
        }
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
            return response()->json(['statusCode'=>401,'message'=>'Data tidak dFoodukan.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'qty' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['statusCode'=>401,'message'=>'You got an error while validating the form.','errors'=>$validator->errors()], 401);
        }
        Food::create([
            'name'=>$request->name,
            'price'=>$request->price,
            'thumb'=>$request->thumb,
            'category_id'=>$request->category_id,
        ]);
        return response()->json(['statusCode'=>200,'message'=>'Data Food has been created.'], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Foods  $plant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (is_null(Order::where('id', $request->order_id)->first())) {
            return response()->json(['statusCode'=>401,'message'=>'Data tidak dFoodukan.'], 401);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'qty' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['statusCode'=>401,'message'=>'You got an error while validating the form.','errors'=>$validator->errors()], 401);
        }
        Food::where('id', $id)->update([
            'name'=>$request->name,
            'price'=>$request->price,
            'qty'=>$request->qty,
            'order_id'=>$request->order_id,
        ]);
        return response()->json(['statusCode'=>200,'message'=>'Data Food has been updated.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Foods  $plant
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Food::where('id', $id)->delete();
        return response()->json(['statusCode'=>200,'message'=>'Data Food has been deleted.'], 200);
    }
}
