<?php

namespace App\Http\Controllers\Frontend\_Author;

use App\Models\Sale;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthorSaleController extends Controller
{
    
    public function store(Request $request)
    {
        
        $course_id = $request->course_id;
        $this->validate($request, [
            'percent' => 'required|numeric|max:100|min:5',
        ]);

        $data = [
            'course_id' => $course_id,
            'percent' => $request->percent,
            'active' => true,
        ];
        
        if($request->expires){
            $data['expires'] = date("Y-m-d",strtotime($request->expires));
        }
       
        $existing_sale = Sale::where('course_id',$course_id)->get()->first();
        if(isset($existing_sale)){
            $existing_sale->update($data);
        }else{
            Sale::create($data);
        }
        return response()->json(null, 200);
        
    }

    public function fetchSales($course)
    {
        $sale = Sale::where('course_id', $course)->get()->first();
        return response()->json($sale, 200);
    }

    public function activate(Request $request, $id)
    {
        $sale = Sale::find($id);
        $request->status==true ? $sale->active=false : $sale->active=true; 
        $sale->save();
        return response()->json($sale, 200);
    }
}
