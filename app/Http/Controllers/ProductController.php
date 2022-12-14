<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function getProducts()
    {
        try {
            Log::info('Getting all products');
            $products = Product::all();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Products retrieved successfully',
                    'data' => $products
                ]
            );

        } catch (\Exception $exception) {
            Log::error("Error retrieving products " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error retrieving products'
                ],
                500
            );
        }
    }

    public function addProduct(Request $request)
    {
        try {
            Log::info('Adding product');
        
            $validator = Validator::make($request->all(), [
                'category_id' => ['required', 'integer'],
                'name' => ['required', 'string', 'max:255', 'min:3'],
                'artist' => ['required', 'string', 'max:255', 'min:3'],
                'year' => ['required', 'integer'],
                'price' => ['required'],
                'description' => ['required'],
                'image' => ['required', 'string'],
                'stock' => ['required', 'integer']
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => $validator->errors()
                    ],
                    400
                );
            }

            $categoryId = $request->input("category_id");
            $productName = $request->input("name");
            $productArtist = $request->input("artist");
            $productYear = $request->input("year");
            $productPrice = $request->input("price");
            $productDescription = $request->input("description");
            $productImage = $request->input("image");
            $productStock = $request->input("stock");

            $product = new Product();
            
            $product->category_id = $categoryId;
            $product->name = $productName;
            $product->artist = $productArtist;
            $product->year = $productYear;
            $product->price = $productPrice;
            $product->description = $productDescription;
            $product->image = $productImage;
            $product->stock = $productStock;

            $product->save();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'New product added'
                ],
                201
            );
        } catch (\Exception $exception) {

            Log::error("Error adding product: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error adding product'
                ],
                500
            );
        }
    }

    public function updateProductById(Request $request, $id){
    
        try {

            Log::info('Updating product');

            $product = Product::find($id);
            
            $validator = Validator::make($request->all(), [
                'name' => ['string', 'max:255', 'min:3'],
                'artist' => ['string', 'max:255', 'min:3'],
                'year' => ['integer'],
                'price' => ['integer'],
                'description' => ['string'],
                'image' => ['string'],
                'stock' => ['integer'],
                'category_id' => ['required']
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => $validator->errors()
                    ],
                    400
                );
            }

            $categoryId = $request->input("category_id");
            $productName = $request->input("name");
            $productArtist = $request->input("artist");
            $productYear = $request->input("year");
            $productPrice = $request->input("price");
            $productDescription = $request->input("description");
            $productImage = $request->input("image");
            $productStock = $request->input("stock");
            

            $product->category_id = $categoryId;
            $product->name = $productName;
            $product->artist = $productArtist;
            $product->year = $productYear;
            $product->price = $productPrice;
            $product->description = $productDescription;
            $product->image = $productImage;
            $product->stock = $productStock;

            $product->save();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Product updated',
                    'data' => $product
                ],
                201
            );
        } catch (\Exception $exception) {

            Log::error("Error updating product: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error updating product'
                ],
                500
            );
        }
    }

    public function deleteProductById($productId){

        try {
        
            Log::info('Deleting product');

            $product = Product::find($productId);

            if (!$product) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => "Missing product"
                    ]
                );
            }

            $product->delete();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Product deleted'
                ],
                200
            );

        } catch (\Exception $exception) {

            Log::error("Error deleting product: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error deleting product'
                ],
                500
            );
        }
    }

    public function getProductById($productId){

        try {
        
            Log::info('Getting product by Id');

            $product = Product::find($productId);

            if (!$product) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => "Missing product"
                    ]
                );
            }

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Product retrieved',
                    'data' => $product
                ],
                200
            );

        } catch (\Exception $exception) {

            Log::error("Error getting product: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error getting product'
                ],
                500
            );
        }
    }

    public function searchProduct($query){

        try {
        
            Log::info('Getting product by ');

            $product = Product::where('name', 'like', '%'.$query.'%')
            ->orWhere('artist', 'like', '%'.$query.'%')
            ->orWhere('year', 'like', '%'.$query.'%')
            ->orWhere('description', 'like', '%'.$query.'%')
            ->get();

            if (!$product) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => "Missing product"
                    ]
                );
            }

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Product retrieved',
                    'data' => $product
                ],
                200
            );

        } catch (\Exception $exception) {

            Log::error("Error getting product: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error getting product'
                ],
                500
            );
        }
    }
    
}
