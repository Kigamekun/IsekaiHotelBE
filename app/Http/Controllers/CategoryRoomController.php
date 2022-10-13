<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

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
            $data = CategoryRoom::where('name', 'LIKE', '%'.$_GET['filter'].'%')->paginate(10);
            return response()->json(['statusCode'=>200,'message'=>'Data CategoryRoom has been obtained.','data'=>$data], 200);
        } else {
            $data = CategoryRoom::paginate(10);
            return response()->json(['statusCode'=>200,'message'=>'Data CategoryRoom has been obtained.','data'=>$data], 200);
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
            return response()->json(['statusCode'=>401,'message'=>'Data tidak dCategoryRoomukan.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['statusCode'=>401,'message'=>'You got an error while validating the form.','errors'=>$validator->errors()], 401);
        }
        CategoryRoom::create([
            'name'=>$request->name,
        ]);
        return response()->json(['statusCode'=>200,'message'=>'Data CategoryRoom has been created.'], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CategoryRooms  $plant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (is_null(Order::where('id', $request->order_id)->first())) {
            return response()->json(['statusCode'=>401,'message'=>'Data tidak dCategoryRoomukan.'], 401);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['statusCode'=>401,'message'=>'You got an error while validating the form.','errors'=>$validator->errors()], 401);
        }
        CategoryRoom::where('id', $id)->update([
            'name'=>$request->name,
        ]);
        return response()->json(['statusCode'=>200,'message'=>'Data CategoryRoom has been updated.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CategoryRooms  $plant
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CategoryRoom::where('id', $id)->delete();
        return response()->json(['statusCode'=>200,'message'=>'Data CategoryRoom has been deleted.'], 200);
    }
}
