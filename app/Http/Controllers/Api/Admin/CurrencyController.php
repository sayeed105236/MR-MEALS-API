<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Currency;


class CurrencyController extends Controller
{
    public function index()
    {
        $currencies= Currency::where('is_deleted',0)->get();
         return response()->json([
        'status'=>200,
        'currencies'=> $currencies
        ]);
       // return response()->json([$currencies]);
    }
  
    
    public function store(Request $request)
    
    {
        $rules = [
        'currency_name' => 'required|string',
         'currency_symbol' => 'required',
        
    ];

    // Validate the request
    $validatedData = $request->validate($rules);

    $currency = new Currency();
    $currency->currency_name = $validatedData['currency_name'];
    $currency->currency_symbol = $validatedData['currency_symbol'];
    $currency->conversion_rate = $request->conversion_rate;
    $currency->save();
     
    // Return the saved data as JSON response
    return response()->json([
        'status'=>200,
        'message'=> 'Currency added successfully',
        'currency' => $currency]);
        
    }
    public function update(Request $request)
    
    {
     
         $rules = [
        'currency_name' => 'required|string',
         'currency_symbol' => 'required',
        
    ];
    // Validate the request
    $validatedData = $request->validate($rules);

  
    $currency = Currency::where('id',$request->id)->first();
    $currency->currency_name = $validatedData['currency_name'];
    $currency->currency_symbol = $validatedData['currency_symbol'];
    $currency->conversion_rate = $request->conversion_rate;
    $currency->status = $request->status;
    $currency->save();
     
    // Return the saved data as JSON response
    return response()->json([
        'status'=>200,
        'message'=> 'Currency updated successfully',
        'currency' => $currency]);
        
    }
    
    public function delete(Request $request)
    {
        $currency= Currency::find($request->id);
        $currency->is_deleted = 1;
        $currency->save();
       return response()->json([
        'status'=>200,
        'message'=> 'Currency deleted successfully'
        ]);
        
        
    }
}
