<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class Product extends Model
{
    use HasFactory;

    
    public function getAllProducts(){
        $products = $this->all();
        return $products;
    }

    public function getOneProduct($id){
        $product = $this->find($id);
        return $product;
    }

    public function storeProduct(Request $request)
    {
            $product = new $this;

            $product->name = $request->name;

            $product->save();

            return $product;
    }

    public function UpdateProduct(Request $request, $id)
    {
            $product = $this->find($id);

            if ($request->has('name')) {
                $product->name = $request->name;
            }

            $product->update();

            return $product;
    }

    private function find($id){
        try {
            $product = $this->findOrFail($id);

            return $product;
        } catch (ModelNotFoundException $th) {
            throw new ModelNotFoundException();
        }
    }

    public function destroyProduct($id){
        $product = $this->find($id);
        $product->delete();

        return $product;
    }
}
