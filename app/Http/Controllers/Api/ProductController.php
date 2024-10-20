<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="API Documentation",
 *     version="0.1.5",
 *     description="This is API Documentation.",
 *     @OA\Contact(
 *         name="Indra Gunawan",
 *         email="nigun1998@gmail.com"
 *     )
 * )
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Product"},
     *     summary="Get list of products",
     *     @OA\Response(response="200", description="A list of products",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product"))
     *     )
     * )
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     summary="Create a new product",
     *     tags={"Product"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "price"},
     *             @OA\Property(property="name", type="string", example="Product Name"),
     *             @OA\Property(property="price", type="number", format="float", example=19.99),
     *             @OA\Property(property="description", type="string", example="Product Description")
     *         )
     *     ),
     *     @OA\Response(response="201", description="Product created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(response="400", description="Invalid input")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        return Product::create($validated);
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     summary="Get product by ID",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Product retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(response="404", description="Product not found")
     * )
     */
    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            return response()->json($product, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     summary="Update a product by ID",
     *     tags={"Product"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Product Name"),
     *             @OA\Property(property="price", type="number", format="float", example=29.99),
     *             @OA\Property(property="description", type="string", example="Updated Product Description")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Product updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product updated successfully"),
     *             @OA\Property(property="product", ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Product not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Failed to update product",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Failed to update product"),
     *             @OA\Property(property="message", type="string", example="Detailed error message")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric',
            'description' => 'nullable|string',
        ]);

        try {
            // Cari product berdasarkan ID, jika tidak ditemukan akan melempar ModelNotFoundException
            $product = Product::findOrFail($id);

            // Update data product
            $product->update($validated);

            // Kembalikan respon berhasil
            return response()->json([
                'message' => 'Product updated successfully',
                'product' => $product
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Jika produk tidak ditemukan, kembalikan respon 404
            return response()->json([
                'error' => 'Product not found'
            ], 404);
        } catch (\Exception $e) {
            // Jika ada error lain, kembalikan respon 500
            return response()->json([
                'error' => 'Failed to update product',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * @OA\Schema(
     *     schema="Product",
     *     type="object",
     *     title="Product",
     *     description="Product model",
     *     @OA\Property(
     *         property="id",
     *         description="Product ID",
     *         type="integer",
     *         format="int64",
     *         example=1
     *     ),
     *     @OA\Property(
     *         property="name",
     *         description="Product name",
     *         type="string",
     *         example="Sample Product"
     *     ),
     *     @OA\Property(
     *         property="price",
     *         description="Product price",
     *         type="number",
     *         format="float",
     *         example=19.99
     *     ),
     *     @OA\Property(
     *         property="description",
     *         description="Product description",
     *         type="string",
     *         nullable=true,
     *         example="This is a sample product description."
     *     )
     * )
     */



    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->noContent();
    }
}
