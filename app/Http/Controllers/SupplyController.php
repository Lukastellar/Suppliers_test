<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Condition;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\Supply;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($search)
    {
        $suppliers = Supplier::where('id', 'LIKE', "%{$search}%")
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->first();

        if($suppliers){
            $suppliers = $suppliers->supplies->makeHidden(['created_at', 'updated_at'] );

            return response()->json($suppliers);
        }
        return response()->json(['msg' => 'Supplier not found.']);
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
        $supply_params = $supply->getAttributes();
        unset($supply_params['condition_id'], $supply_params['category_id']);
        $parameters = $request->query();
        $column_err = ['msg' => []];

        foreach($parameters as $param => $value) {

            if ($param == 'condition_id') {
                if(Condition::find($value)){
                    $supply->update(['condition_id' => $value]);
                    array_push($column_err['msg'], 'Updated condition with value '."'$value'");
                    continue;
                }
                array_push($column_err['msg'], "Something's wrong with value"." '$value' ".'of param '."'$param'");
                continue;
            }
            if ($param == 'category_id') {
                if(Category::find($value)){
                    $supply->update(['category_id' => $value]);
                    array_push($column_err['msg'], 'Updated category with value of '."'$value'");
                    continue;
                }
                array_push($column_err['msg'], "Something's wrong with value"." '$value' ".'of param '."'$param'");
                continue;
            }
            if (array_key_exists($param, $supply_params)) {
                $supply->update([$param => $value]);
                array_push($column_err['msg'], 'Updated '."'$param'");
            }
        }

        return response()->json([$column_err]);
    }

    /**
     * Remove the specified resource from storage.

     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Supply::find($id)->delete();
    }

    public function generateCSV($search){
        date_default_timezone_set('Europe/Belgrade');

        $data = Supplier::where('id', 'LIKE', "%{$search}%")
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->first();

        // Return if supplier not found
        if(!$data){
            return json_encode(['msg' => 'Entered supplier does not exist.']);
        }

        // Make file name
        $current_date = date('_Y_m_d_H_i_s');
        $supplier_name = preg_replace('/[^a-zA-Z0-9\s]|[\s+]/', '_', $data->name);
        $filename = $supplier_name.$current_date.'.csv';

        // Header
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        // Format data
        $data = $data->supplies;

        ///////////// Open file ////////////////
        $callback = function() use ($filename, $data) {
        $file = fopen('php://output', 'w');

        // Throw error
        if ($file === false) { return json_encode(['msg' => 'Error opening file'.$filename]); }

        // Create header
        $csv_header = ['days_valid', 'priority', 'part_number', 'part_desc', 'quantity', 'price', 'condition', 'category'];
        fputcsv($file, $csv_header);

        // Columns
        $columns = ['days_valid', 'priority', 'part_number', 'part_desc', 'quantity', 'price', 'condition', 'category'];

        // Create rows
        foreach ($data as $query) {
            $columns[0] = $query->days_valid;
            $columns[1] = $query->priority;
            $columns[2] = $query->part_number;
            $columns[3] = $query->part_desc;
            $columns[4] = $query->quantity;
            $columns[5] = $query->price;
            $columns[6] = $query->condition->name;
            $columns[7] = $query->category->name;

            fputcsv($file, $columns);
        }
        // Close file
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

