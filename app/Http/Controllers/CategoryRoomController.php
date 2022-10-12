<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryRoomController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (isset($_GET['filter'])) {
            $data = Hotel::where('name', 'LIKE', '%'.$_GET['filter'].'%')->paginate(10);
            return response()->json(['statusCode'=>200,'message'=>'Data Hotel has been obtained.','data'=>$data], 200);
        } else {
            $data = Hotel::paginate(10);
            return response()->json(['statusCode'=>200,'message'=>'Data Hotel has been obtained.','data'=>$data], 200);
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
            return response()->json(['statusCode'=>401,'message'=>'Data tidak dHotelukan.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'qty' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['statusCode'=>401,'message'=>'You got an error while validating the form.','errors'=>$validator->errors()], 401);
        }
        Hotel::create([
            'name'=>$request->name,
            'price'=>$request->price,
            'qty'=>$request->qty,
            'order_id'=>$request->order_id,
        ]);
        return response()->json(['statusCode'=>200,'message'=>'Data Hotel has been created.'], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hotels  $plant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (is_null(Order::where('id', $request->order_id)->first())) {
            return response()->json(['statusCode'=>401,'message'=>'Data tidak dHotelukan.'], 401);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'qty' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['statusCode'=>401,'message'=>'You got an error while validating the form.','errors'=>$validator->errors()], 401);
        }
        Hotel::where('id', $id)->update([
            'name'=>$request->name,
            'price'=>$request->price,
            'qty'=>$request->qty,
            'order_id'=>$request->order_id,
        ]);
        return response()->json(['statusCode'=>200,'message'=>'Data Hotel has been updated.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hotels  $plant
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Hotel::where('id', $id)->delete();
        return response()->json(['statusCode'=>200,'message'=>'Data Hotel has been deleted.'], 200);
    }
}
