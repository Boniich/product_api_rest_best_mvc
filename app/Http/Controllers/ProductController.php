<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ProductController extends Controller
{
    private Product $product;

    public function __construct(Product $product) {
        $this->product = $product;
    }

    public function index(){
        $data = $this->product->getAllProducts();
        return response()->json(['success' => true, 'data' => $data, 'message' => "Products retrived successfully"],200);
    }

    public function show($id){
        try {
            $data = $this->product->getOneProduct($id);
            
            return response()->json(['success' => true, 'data' => $data, 'message' => "Product retrived successfully"],200);

        } catch (ModelNotFoundException $th) {
            return response()->json(['success' => false, 'error' => "Product not found"],404);
        }
    }

    public function store(Request $request){
        
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                throw new BadRequestException();
            }
    
            $newData = $this->product->storeProduct($request);

            return response()->json(['success' => true, 'data' => $newData, 'message' => "Product created successfully"],201);

        } catch (BadRequestException $th) {
            return response()->json(['success' => false, 'error' => "Bad Request"],400);
        }


    }

    public function update(Request $request,$id){
        try {
           
            $validator = Validator::make($request->all(), [
                'name' => 'string',
            ]);

            if ($validator->fails()) {
                throw new BadRequestException;
            }

            $productUpdated = $this->product->updateProduct($request,$id);

            return response()->json(['success' => true, 'data' => $productUpdated, 'message' => "Product updated successfully"],200);

        } catch (BadRequestException $th) {
            return response()->json(['success' => false, 'error' => "Bad Request"],400);
        } catch (ModelNotFoundException){
            return response()->json(['success' => false, 'error' => "Product not found"],404);
        }
    }

    public function destroy($id){
        try {
           $product = $this->product->destroyProduct($id);

           return response()->json(['success' => true, 'data' => $product, 'message' => "Product deleted successfully"],200);
        } catch (ModelNotFoundException $th) {
            return response()->json(['success' => false, 'error' => "Product not found"],404);
        }
    }
}
