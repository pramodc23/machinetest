<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
	public function index()
    {    	
        return view('product');
    }

    public function importdata(Request $request)
    {    	
    	
    	dd($request->input('csvFile'));
        $insertdata = [
            'house'  			=> $request->input('name'),
            'street'       		=> 'test',
            'unit'       		=> '23 ',
            'price'       		=> '12345',
            'beds'       		=> '10',
            'baths'       		=> '45',
            'city'       		=> 'indore',
        ];

        $product = Product::create($insertdata);


        if ($product->exists) {
        	 return response()->json(['status' => true, 'message' => 'File Import successfully']);
        } else {
            return response()->json(['status' => false, 'errors' => "Something went worng"]);
        }
    }

    public function test()
    {
        $insertdata = [
            'house'  			=> '25 new avenue',
            'street'       		=> 'GB street',
            'unit'       		=> '23 ',
            'price'       		=> '12345',
            'beds'       		=> '10',
            'baths'       		=> '45',
            'city'       		=> 'indore',
        ];

        $product = Product::create($insertdata);
    	
    	dd($product);
    }

}
