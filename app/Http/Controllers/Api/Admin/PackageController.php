<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\GeneralException;

class PackageController extends Controller
{
    public function index()
    {
        $packages= Package::select('packages.*','package_categories.category_name','currencies.currency_symbol')
        ->join('package_categories','package_categories.id','packages.package_category_id')
       
        ->join('currencies','currencies.id','packages.currency_id')
        ->where('packages.is_deleted',0)->get();
        foreach($packages as $package)
        {
            $package->image = url('/public/storage/packages/'.$package->image);
        }
        return response()->json([
        'status'=>200,
        'packages'=> $packages
        ]);
    }
  
    
    public function store(Request $request)
    
    {
        $rules = [
        'package_name' => 'required|string',
       
        'package_category_id' => 'required',
        'package_price' => 'required',
        'package_qty' => 'required',
     
        'currency_id' => 'required',
         'short_description' => 'required',
         'long_description'=>'required',
         
        
    ];
   // dd($request->file('category_icon'));

    // Validate the request
    $validatedData = $request->validate($rules);
   
    $image = $request->file('image');
 
    $filename2 = null;
    
    if ($image) {
        $filename2 = time() . $image->getClientOriginalName();

        Storage::disk('public')->putFileAs(
            'packages/',
            $image,
            $filename2
        );
    }
    $package = new Package();
    $package->package_name = $validatedData['package_name'];
    $package->package_price = $validatedData['package_price'];
    $package->package_category_id = $validatedData['package_category_id'];
    $package->package_qty = $validatedData['package_qty'];
    $package->currency_id = $validatedData['currency_id'];
    $package->discount_price = $request->discount_price;
    $package->short_description = $validatedData['short_description'];
    $package->image = $filename2;
    $package->long_description = $validatedData['long_description'];
    //$package->health_tips = $request->health_tips;
    //$package->sub_category_id = $request->sub_category_id;
    $package->save();
     
    // Return the saved data as JSON response
    return response()->json([
        'status'=>200,
        'message'=> 'Package added successfully',
        'package' => $package]);
        
    }
    public function update(Request $request)
    
    {
       
       
        $rules = [
        'package_name' => 'required|string',
       
        'package_category_id' => 'required',
        'package_price' => 'required',
        'package_qty' => 'required',
     
        'currency_id' => 'required',
         'short_description' => 'required',
         'long_description'=>'required',
         
        
    ];
    // Validate the request
    $validatedData = $request->validate($rules);
    $package= Package::where('id',$request->id)->first();
   
     if($request->file('image') != null)
    {
      $image =$request->file('image');
      $filename2=null;
      $uploadedFile2 = $request->file('image');
      $oldfilename2 = $item['image'] ?? 'demo.jpg';

      $oldfileexists2 = Storage::disk('public')->exists('packages/' . $oldfilename2);

      if ($uploadedFile2 !== null) {

          if ($oldfileexists2 && $oldfilename2 != $uploadedFile2) {
              //Delete old file
              Storage::disk('public')->delete('packages/' . $oldfilename2);
          }
          $filename_modified2 = str_replace(' ', '_', $uploadedFile2->getClientOriginalName());
          $filename2 = time() . '_' . $filename_modified2;

          Storage::disk('public')->putFileAs(
              'packages/',
              $uploadedFile2,
              $filename2
          );

          $data['image'] = $filename2;
       } elseif (empty($oldfileexists2)) {
          // throw new \Exception('Client image not found!');
          $uploadedFile2 = null;
          
           return response()->json([
        'status'=>400,
        'message'=> 'Package icon not found',
       ]);

          //file check in storage
        }
    }

  
      
      if ($request->file('image') != null) {
        $item->image= $filename2;
      }

    $package->package_name = $validatedData['package_name'];
    $package->package_price = $validatedData['package_price'];
    $package->package_category_id = $validatedData['package_category_id'];
    $package->package_qty = $validatedData['package_qty'];
    $package->currency_id = $validatedData['currency_id'];
    $package->discount_price = $request->discount_price;
    $package->short_description = $validatedData['short_description'];
    $package->image = $filename2;
    $package->long_description = $validatedData['long_description'];
    $package->status = $request->status;
    $package->save();
     
     
    // Return the saved data as JSON response
    return response()->json([
        'status'=>200,
        'message'=> 'Package updated successfully',
        'package' => $package]);
        
    }
    
    public function delete(Request $request)
    {
        $package= Package::find($request->id);
        $package->is_deleted = 1;
        $package->save();
       return response()->json([
        'status'=>200,
        'message'=> 'Package deleted successfully'
        ]);
        
        
    }
    public function PackageDetail(Request $request)
    {
          
        $package= Package::select('packages.*','package_categories.category_name','currencies.currency_symbol')
        ->join('package_categories','package_categories.id','packages.package_category_id')
       
        ->join('currencies','currencies.id','packages.currency_id')
        ->where('packages.id',$request->package_id)->first();
        if($package == null)
        {
             return response()->json([
        'status'=>400,
        'message'=> 'Package not found'
        ]);
        }
        
        $package->image = url('/public/storage/packages/'.$package->image);
         return response()->json([
        'status'=>200,
        'package'=> $package
        ]);
        
        
        
    }
}
