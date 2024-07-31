<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PackageCategory;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\GeneralException;

class PackageCategoryController extends Controller
{
     public function index()
    {
        $package_categories= PackageCategory::where('is_deleted',0)->get();
        foreach($package_categories as $package_category)
        {
            $package_category->category_icon = url('/public/storage/package_categories/'.$package_category->category_icon);
        }
         return response()->json([
        'status'=>200,
        'package_categories'=> $package_categories
        ]);
    }
  
    
    public function store(Request $request)
    
    {
        $rules = [
        'category_name' => 'required|string',
        
         
        
    ];
   // dd($request->file('category_icon'));

    // Validate the request
    $validatedData = $request->validate($rules);
   
    $category_icon = $request->file('category_icon');
   
    $filename2 = null;
   
    if ($category_icon) {
        $filename2 = time() . $category_icon->getClientOriginalName();

        Storage::disk('public')->putFileAs(
            'package_categories/',
            $category_icon,
            $filename2
        );
    }
    $package_category = new PackageCategory();
    $package_category->category_name = $validatedData['category_name'];
    
    //$category->category_image = $filename;
    $package_category->category_icon = $filename2;
    $package_category->start_time = $request->start_time;
    $package_category->end_time = $request->end_time;
   
    $package_category->save();
     
    // Return the saved data as JSON response
    return response()->json([
        'status'=>200,
        'message'=> 'Package category added successfully',
        'package_category' => $package_category]);
        
    }
    public function update(Request $request)
    
    {
     $rules = [
        'category_name' => 'required|string',
         
        
    ];
    // Validate the request
    $validatedData = $request->validate($rules);
    $package_category= PackageCategory::where('id',$request->id)->first();
    
     if($request->file('category_icon') != null)
    {
      $category_icon =$request->file('category_icon');
      $filename2=null;
      $uploadedFile2 = $request->file('category_icon');
      $oldfilename2 = $category['category_icon'] ?? 'demo.jpg';

      $oldfileexists2 = Storage::disk('public')->exists('package_categories/' . $oldfilename2);

      if ($uploadedFile2 !== null) {

          if ($oldfileexists2 && $oldfilename2 != $uploadedFile2) {
              //Delete old file
              Storage::disk('public')->delete('package_categories/' . $oldfilename2);
          }
          $filename_modified2 = str_replace(' ', '_', $uploadedFile2->getClientOriginalName());
          $filename2 = time() . '_' . $filename_modified2;

          Storage::disk('public')->putFileAs(
              'package_categories/',
              $uploadedFile2,
              $filename2
          );

          $data['category_icon'] = $filename2;
       } elseif (empty($oldfileexists2)) {
          // throw new \Exception('Client image not found!');
          $uploadedFile2 = null;
          
           return response()->json([
        'status'=>400,
        'message'=> 'Package Category icon not found',
       ]);

          //file check in storage
        }
    }

     
    //   if ($request->file('category_image') != null) {
    //     $category->category_image= $filename;
    //   }
      
      if ($request->file('category_icon') != null) {
        $package_category->category_icon= $filename2;
      }

  
  
    $package_category->category_name = $validatedData['category_name'];
    
  //  $category->category_image = $filename;
    $package_category->category_icon = $filename2;
    $package_category->start_time = $request->start_time;
    $package_category->end_time = $request->end_time;
    $package_category->status = $request->status;
   
    $package_category->save();
     
     
    // Return the saved data as JSON response
    return response()->json([
        'status'=>200,
        'message'=> 'Package Category updated successfully',
        'package_category' => $package_category]);
        
    }
    
    public function delete(Request $request)
    {
        $package_category= PackageCategory::find($request->id);
        $package_category->is_deleted = 1;
        $package_category->save();
       return response()->json([
        'status'=>200,
        'message'=> 'Package Category deleted successfully'
        ]);
        
        
    }
}
