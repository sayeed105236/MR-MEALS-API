<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\GeneralException;


class SliderController extends Controller
{
    public function index()
    {
        $sliders= Slider::where('is_deleted',0)->get();
        foreach($sliders as $slider)
        {
            $slider->image = url('/public/storage/sliders/'.$slider->image);
        }
        return response()->json([
        'status'=>200,
        'sliders'=> $sliders
        ]);
    }
  
    
    public function store(Request $request)
    
    {
        $rules = [
        'page_name' => 'required|string',
         'image' => 'required',

    ];
   
    // Validate the request
    $validatedData = $request->validate($rules);
   
    $image = $request->file('image');
   
    $filename = null;
    if ($image) {
        $filename = time() . $image->getClientOriginalName();

        Storage::disk('public')->putFileAs(
            'sliders/',
            $image,
            $filename
        );
    }
    
    $slider = new Slider();
    $slider->page_name = $validatedData['page_name'];
    $slider->slider_text = $request->slider_text;
    
    $slider->image = $filename;
   
    $slider->save();
     
    // Return the saved data as JSON response
    return response()->json([
        'status'=>200,
        'message'=> 'Slider added successfully',
        'slider' => $slider]);
        
    }
    public function update(Request $request)
    
{
        $rules = [
        'page_name' => 'required|string',
         'image' => 'required',

    ];
    // Validate the request
    $validatedData = $request->validate($rules);
    $slider= Slider::where('id',$request->id)->first();
    if($request->file('image') != null)
    {
      $image =$request->file('image');
      $filename=null;
      $uploadedFile = $request->file('image');
      $oldfilename = $category['image'] ?? 'demo.jpg';

      $oldfileexists = Storage::disk('public')->exists('sliders/' . $oldfilename);

      if ($uploadedFile !== null) {

          if ($oldfileexists && $oldfilename != $uploadedFile) {
              //Delete old file
              Storage::disk('public')->delete('sliders/' . $oldfilename);
          }
          $filename_modified = str_replace(' ', '_', $uploadedFile->getClientOriginalName());
          $filename = time() . '_' . $filename_modified;

          Storage::disk('public')->putFileAs(
              'sliders/',
              $uploadedFile,
              $filename
          );

          $data['image'] = $filename;
      } elseif (empty($oldfileexists)) {
          // throw new \Exception('Client image not found!');
          $uploadedFile = null;
          
          return response()->json([
        'status'=>400,
        'message'=> 'Slider image not found',
      ]);

          //file check in storage
        }
    }
   
     
      if ($request->file('image') != null) {
        $slider->image= $filename;
      }
      

  
  
    $slider->page_name = $validatedData['page_name'];
    
 
    $slider->image = $filename;
    $slider->slider_text = $request->slider_text;
    $slider->status = $request->status;
   
    $slider->save();
     
     
    // Return the saved data as JSON response
    return response()->json([
        'status'=>200,
        'message'=> 'Slider updated successfully',
        'slider' => $slider]);
        
    }
    
    public function delete(Request $request)
    {
        $slider= Slider::find($request->id);
        $slider->is_deleted = 1;
        $slider->save();
       return response()->json([
        'status'=>200,
        'message'=> 'Slider deleted successfully'
        ]);
        
        
    }
}
