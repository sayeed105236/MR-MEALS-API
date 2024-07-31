<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Item;
use App\Models\Package;
use App\Models\Category;
use App\Models\PackageCategory;

class FrontendController extends Controller
{
    public function sliders(Request $request)
    {
          $sliders= Slider::where('is_deleted',0)->where('status',1)->where('page_name',$request->page_name)->get();
        foreach($sliders as $slider)
        {
            $slider->image = url('/public/storage/sliders/'.$slider->image);
        }
        return response()->json([
            'status'=>200,
            'sliders'=> $sliders]);
        
    }
     public function items(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $items= Item::select('items.id','items.item_name','items.item_price','items.image','items.discount_price','categories.category_name','sub_categories.subcategory_name','currencies.currency_symbol')
        ->join('categories','categories.id','items.category_id')
        ->join('sub_categories','sub_categories.id','items.sub_category_id')
        ->join('currencies','currencies.id','items.currency_id')
        ->where('items.is_deleted',0)->where('items.status',1)->paginate($perPage);
        foreach($items as $item)
        {
            $item->image = url('/public/storage/items/'.$item->image);
        }
        return response()->json([
            'status'=>200,
            'items'=>$items]);
        
    }
    public function itemDetails(Request $request)
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
            'item'=>$item]);
    }
    public function packages(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $packages= Package::select('packages.id','packages.package_price','packages.package_category_id','packages.discount_price','packages.image','package_categories.category_name','currencies.currency_symbol','packages.short_description')
        ->join('package_categories','package_categories.id','packages.package_category_id')
       
        ->join('currencies','currencies.id','packages.currency_id')
        ->where('packages.is_deleted',0)->where('packages.status',1)->orderBy('id','desc')->paginate($perPage);
        foreach($packages as $package)
        {
            $package->image = url('/public/storage/packages/'.$package->image);
        }
        return response()->json(['status'=>200,'package'=>$packages]);
        
    }
    public function packageDetails(Request $request)
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
        
        return response()->json(['status'=>200,'package'=>$package]);
            
        
         
    }
    public function categories()
    {
        $categories= Category::select('categories.category_name','categories.category_icon')->where('is_deleted',0)->orderBy('id','desc')->get();
        foreach($categories as $category)
        {
            $category->category_icon = url('/public/storage/categories/'.$category->category_icon);
        }
        return response()->json(['status'=>200,'categories'=>$categories]);
    }
    public function itemByCategories(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $data['categories'] = Category::select('categories.id','categories.category_name','categories.start_time','categories.end_time')
        ->where('categories.is_deleted', 0)
        ->where('categories.status',1)
      //  ->orderBy('categories.id', 'desc')
        ->get();
        
        foreach ($data['categories'] as $category) {
        $category->items = Item::select(
        'items.id as item_id',
        'items.item_name',
        'items.image',
        'items.item_price',
        'items.discount_price',
        'currencies.currency_symbol'
        )
        ->leftJoin('currencies', 'currencies.id', 'items.currency_id')
        ->where('items.category_id', $category->id)
        ->where('items.is_deleted', 0)
        ->where('items.status',1)
        ->where('currencies.status', 1)
        ->orderBy('items.id','desc')
        ->paginate($perPage);
      //  dd($category->items);
        
        foreach ($category->items as $item) {
        $item->image = url('/public/storage/items/' . $item->image);
        }
        }
        
        return response()->json(['status' => 200, 'data' => $data]);
        
        
        
    }
     public function packageByCategories(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $data['categories'] = PackageCategory::select('package_categories.id','package_categories.category_name','package_categories.start_time','package_categories.end_time','package_categories.category_icon')
        ->where('package_categories.is_deleted', 0)
        ->where('package_categories.status',1)
        //->orderBy('package_categories.id', 'desc')
        ->get();
        
        foreach ($data['categories'] as $category) {
        $category->packages = Package::select(
        'packages.id as package_id',
        'packages.package_name',
        'packages.image',
        'packages.package_price',
        'packages.discount_price',
        'currencies.currency_symbol'
        )
        ->leftJoin('currencies', 'currencies.id', 'packages.currency_id')
        ->where('packages.package_category_id', $category->id)
        ->where('packages.is_deleted', 0)
        ->where('packages.status', 1)
        ->where('currencies.status', 1)
        ->orderBy('packages.id','desc')
        ->paginate($perPage);
        
        foreach ($category->packages as $package) {
        $package->image = url('/public/storage/packages/' . $package->image);
        }
        }
        
        return response()->json(['status' => 200, 'data' => $data]);
        
        
        
    }
       
}
