<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Condition;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\Supply;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;

class SupplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $suppliers = Supply::all();
        $suppliers->makeHidden(['created_at', 'updated_at'] );

        return response()->json($suppliers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($search)
    {
        $suppliers = Supplier::where('id', 'LIKE', "%{$search}%")
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->first()
            ->supplies;
        $suppliers->makeHidden(['created_at', 'updated_at'] );

        return response()->json($suppliers);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id)
    {
        $supply = Supply::find($id)->makeHidden(['id', 'supplier_id', 'created_at', 'updated_at']);
        $parameters = $request->query();
        $column_err = [];

        foreach($parameters as $key => $param){

            if(Schema::hasColumn('supplies', $key)){
                if( $key == 'condition_id' && !Condition::find($param) ) {
                    array_push($column_err, $key.': '.$param);
                    continue;
                }
                if( $key == 'category_id' && !Category::find($param) ) {
                    array_push($column_err, $key.': '.$param);
                    continue;
                    }
                $supply->update([$key => $param]);
            } else {
                array_push($column_err, $key);
            }
        }
        if(empty($column_err)){
            return response()->json(['msg' => 'Product updated!']);
        }
        return response()->json(['msg' => ['Invalid parameters or value' => $column_err]]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Supply::find($id)->delete();
    }
}

