<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\GeneralException;

class CategoryController extends Controller
{
       public function index()
    {
        $categories= Category::where('is_deleted',0)->get();
        foreach($categories as $category)
        {
            $category->category_icon = url('/public/storage/categories/'.$category->category_icon);
        }
        return response()->json([$categories]);
    }
  
    
    public function store(Request $request)
    
    {
        $rules = [
        'category_name' => 'required|string',
         'category_icon' => 'required',
        // 'category_image'=> 'required',
         'start_time'=>'required',
         'end_time'=> 'required',
         
        
    ];
   // dd($request->file('category_icon'));

    // Validate the request
    $validatedData = $request->validate($rules);
   // $category_image = $request->file('category_image');
    $category_icon = $request->file('category_icon');
   // $filename = null;
    $filename2 = null;
    // if ($category_image) {
    //     $filename = time() . $category_image->getClientOriginalName();

    //     Storage::disk('public')->putFileAs(
    //         'categories/',
    //         $category_image,
    //         $filename
    //     );
    // }
    if ($category_icon) {
        $filename2 = time() . $category_icon->getClientOriginalName();

        Storage::disk('public')->putFileAs(
            'categories/',
            $category_icon,
            $filename2
        );
    }
    $category = new Category();
    $category->category_name = $validatedData['category_name'];
    
    //$category->category_image = $filename;
    $category->category_icon = $filename2;
    $category->start_time = $validatedData['start_time'];
    $category->end_time = $validatedData['end_time'];
   
    $category->save();
     
    // Return the saved data as JSON response
    return response()->json([
        'status'=>200,
        'message'=> 'Currency added successfully',
        'category' => $category]);
        
    }
    public function update(Request $request)
    
    {
     $rules = [
        'category_name' => 'required|string',
         'category_icon' => 'required',
       //  'category_image'=> 'required',
         'start_time'=>'required',
         'end_time'=> 'required',
         
        
    ];
    // Validate the request
    $validatedData = $request->validate($rules);
    $category= Category::where('id',$request->id)->first();
    // if($request->file('category_image') != null)
    // {
    //   $category_image =$request->file('category_image');
    //   $filename=null;
    //   $uploadedFile = $request->file('category_image');
    //   $oldfilename = $category['category_image'] ?? 'demo.jpg';

    //   $oldfileexists = Storage::disk('public')->exists('categories/' . $oldfilename);

    //   if ($uploadedFile !== null) {

    //       if ($oldfileexists && $oldfilename != $uploadedFile) {
    //           //Delete old file
    //           Storage::disk('public')->delete('categories/' . $oldfilename);
    //       }
    //       $filename_modified = str_replace(' ', '_', $uploadedFile->getClientOriginalName());
    //       $filename = time() . '_' . $filename_modified;

    //       Storage::disk('public')->putFileAs(
    //           'categories/',
    //           $uploadedFile,
    //           $filename
    //       );

    //       $data['category_image'] = $filename;
    //   } elseif (empty($oldfileexists)) {
    //       // throw new \Exception('Client image not found!');
    //       $uploadedFile = null;
          
    //       return response()->json([
    //     'status'=>400,
    //     'message'=> 'Category image not found',
    //   ]);

    //       //file check in storage
    //     }
    // }
     if($request->file('category_icon') != null)
    {
      $category_icon =$request->file('category_icon');
      $filename2=null;
      $uploadedFile2 = $request->file('category_icon');
      $oldfilename2 = $category['category_icon'] ?? 'demo.jpg';

      $oldfileexists2 = Storage::disk('public')->exists('categories/' . $oldfilename2);

      if ($uploadedFile2 !== null) {

          if ($oldfileexists2 && $oldfilename2 != $uploadedFile2) {
              //Delete old file
              Storage::disk('public')->delete('categories/' . $oldfilename2);
          }
          $filename_modified2 = str_replace(' ', '_', $uploadedFile2->getClientOriginalName());
          $filename2 = time() . '_' . $filename_modified2;

          Storage::disk('public')->putFileAs(
              'categories/',
              $uploadedFile2,
              $filename2
          );

          $data['$category_icon'] = $filename2;
       } elseif (empty($oldfileexists2)) {
          // throw new \Exception('Client image not found!');
          $uploadedFile2 = null;
          
           return response()->json([
        'status'=>400,
        'message'=> 'Category icon not found',
       ]);

          //file check in storage
        }
    }

     
    //   if ($request->file('category_image') != null) {
    //     $category->category_image= $filename;
    //   }
      
      if ($request->file('category_icon') != null) {
        $category->category_icon= $filename2;
      }

  
  
    $category->category_name = $validatedData['category_name'];
    
  //  $category->category_image = $filename;
    $category->category_icon = $filename2;
    $category->start_time = $validatedData['start_time'];
    $category->end_time = $validatedData['end_time'];
    $category->status = $request->status;
   
    $category->save();
     
     
    // Return the saved data as JSON response
    return response()->json([
        'status'=>200,
        'message'=> 'Category updated successfully',
        'category' => $category]);
        
    }
    
    public function delete(Request $request)
    {
        $category= Category::find($request->id);
        $category->is_deleted = 1;
        $category->save();
       return response()->json([
        'status'=>200,
        'message'=> 'Category deleted successfully'
        ]);
        
        
    }
}
