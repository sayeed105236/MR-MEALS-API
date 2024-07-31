<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subcategories= SubCategory::select('sub_categories.subcategory_name',
        'sub_categories.id','sub_categories.category_id','sub_categories.status','sub_categories.created_at','sub_categories.updated_at','categories.category_name')
            
        ->join('categories','categories.id','sub_categories.category_id')
        
        ->where('sub_categories.is_deleted',0)->get();
        
         return response()->json([
        'status'=>200,
        'subcategories'=> $subcategories
        ]);
    }
  
    
    public function store(Request $request)
    
    {
        $rules = [
        'subcategory_name' => 'required|string',
         'category_id' => 'required',
        
         
        
    ];
   // dd($request->file('category_icon'));

    // Validate the request
    $validatedData = $request->validate($rules);
   
    $subcategory = new SubCategory();
    $subcategory->subcategory_name = $validatedData['subcategory_name'];
    $subcategory->category_id = $validatedData['category_id'];
    $subcategory->save();
     
    // Return the saved data as JSON response
    return response()->json([
        'status'=>200,
        'message'=> 'Sub Category added successfully',
        'subcategory' => $subcategory]);
        
    }
    public function update(Request $request)
    
    {
      $rules = [
        'subcategory_name' => 'required|string',
         'category_id' => 'required',
    
    ];
    // Validate the request
    $validatedData = $request->validate($rules);
    $subcategory= SubCategory::where('id',$request->id)->first();

    $subcategory->subcategory_name = $validatedData['subcategory_name'];
    
    $subcategory->category_id = $validatedData['category_id'];
    $subcategory->status = $request->status;
   
    $subcategory->save();
     
     
    // Return the saved data as JSON response
    return response()->json([
        'status'=>200,
        'message'=> 'SubCategory updated successfully',
        'subcategory' => $subcategory]);
        
    }
    
    public function delete(Request $request)
    {
        $subcategory= SubCategory::find($request->id);
        $subcategory->is_deleted = 1;
        $subcategory->save();
       return response()->json([
        'status'=>200,
        'message'=> 'SubCategory deleted successfully'
        ]);
        
        
    }
}
