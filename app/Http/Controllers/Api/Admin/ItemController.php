<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\GeneralException;

class ItemController extends Controller
{
    public function index()
    {
        $items= Item::select('items.*','categories.category_name','sub_categories.subcategory_name','currencies.currency_symbol')
        ->join('categories','categories.id','items.category_id')
        ->join('sub_categories','sub_categories.id','items.sub_category_id')
        ->join('currencies','currencies.id','items.currency_id')
        ->where('items.is_deleted',0)->get();
        foreach($items as $item)
        {
            $item->image = url('/public/storage/items/'.$item->image);
        }
        return response()->json([
        'status'=>200,
        'items'=> $items
        ]);
    }
  
    
    public function store(Request $request)
    
    {
        $rules = [
        'item_name' => 'required|string',
        'image' => 'required',
        'category_id' => 'required',
         'item_price' => 'required',
        'item_qty' => 'required',
       // 'discount_price' => 'required',
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
            'items/',
            $image,
            $filename2
        );
    }
    $item = new Item();
    $item->item_name = $validatedData['item_name'];
    $item->item_price = $validatedData['item_price'];
    $item->category_id = $validatedData['category_id'];
    $item->item_qty = $validatedData['item_qty'];
    $item->currency_id = $validatedData['currency_id'];
    $item->discount_price = $request->discount_price;
    $item->short_description = $validatedData['short_description'];
    
    //$category->category_image = $filename;
    $item->image = $filename2;
    $item->long_description = $validatedData['long_description'];
    $item->health_tips = $request->health_tips;
    $item->sub_category_id = $request->sub_category_id;
   
    $item->save();
     
    // Return the saved data as JSON response
    return response()->json([
        'status'=>200,
        'message'=> 'Item added successfully',
        'item' => $item]);
        
    }
    public function update(Request $request)
    
    {
       
        $rules = [
        'item_name' => 'required|string',
        'image' => 'required',
        'category_id' => 'required',
         'item_price' => 'required',
        'item_qty' => 'required',
        //'discount_price' => 'required',
        'currency_id' => 'required',
         'short_description' => 'required',
         'long_description'=>'required',
         
        
    ];
    // Validate the request
    $validatedData = $request->validate($rules);
    $item= Item::where('id',$request->id)->first();
   
     if($request->file('image') != null)
    {
      $image =$request->file('image');
      $filename2=null;
      $uploadedFile2 = $request->file('image');
      $oldfilename2 = $item['image'] ?? 'demo.jpg';

      $oldfileexists2 = Storage::disk('public')->exists('items/' . $oldfilename2);

      if ($uploadedFile2 !== null) {

          if ($oldfileexists2 && $oldfilename2 != $uploadedFile2) {
              //Delete old file
              Storage::disk('public')->delete('items/' . $oldfilename2);
          }
          $filename_modified2 = str_replace(' ', '_', $uploadedFile2->getClientOriginalName());
          $filename2 = time() . '_' . $filename_modified2;

          Storage::disk('public')->putFileAs(
              'items/',
              $uploadedFile2,
              $filename2
          );

          $data['image'] = $filename2;
       } elseif (empty($oldfileexists2)) {
          // throw new \Exception('Client image not found!');
          $uploadedFile2 = null;
          
           return response()->json([
        'status'=>400,
        'message'=> 'Item icon not found',
       ]);

          //file check in storage
        }
    }

  
      
      if ($request->file('image') != null) {
        $item->image= $filename2;
      }

    $item->item_name = $validatedData['item_name'];
    $item->item_price = $validatedData['item_price'];
    $item->item_qty = $validatedData['item_qty'];
    $item->category_id = $validatedData['category_id'];
    $item->currency_id = $validatedData['currency_id'];
    $item->discount_price = $request->discount_price;
    $item->short_description = $validatedData['short_description'];
    $item->image = $filename2;
    $item->long_description = $validatedData['long_description'];
    $item->health_tips = $request->health_tips;
    $item->sub_category_id = $request->sub_category_id;
    $item->save();
     
     
    // Return the saved data as JSON response
    return response()->json([
        'status'=>200,
        'message'=> 'Item updated successfully',
        'item' => $item]);
        
    }
    
    public function delete(Request $request)
    {
        $item= Item::find($request->id);
        $item->is_deleted = 1;
        $item->save();
       return response()->json([
        'status'=>200,
        'message'=> 'Item deleted successfully'
        ]);
        
        
    }
    public function ItemDetail(Request $request)
    {
        
        $item= Item::select('items.*','categories.category_name','sub_categories.subcategory_name','currencies.currency_symbol')
        ->join('categories','categories.id','items.category_id')
        ->join('sub_categories','sub_categories.id','items.sub_category_id')
        ->join('currencies','currencies.id','items.currency_id')
        ->where('items.id',$request->item_id)
        ->first();
          if($item == null)
        {
             return response()->json([
        'status'=>400,
        'message'=> 'Item not found'
        ]);
        }
        
        $item->image = url('/public/storage/items/'.$item->image);
        
         return response()->json([
        'status'=>200,
        'item'=> $item
        ]);
        
    }
}
