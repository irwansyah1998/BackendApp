<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;



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
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         description="Timestamp when the product was created",
 *         type="string",
 *         format="date-time",
 *         example="2024-01-01T12:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         description="Timestamp when the product was last updated",
 *         type="string",
 *         format="date-time",
 *         example="2024-01-02T12:00:00Z"
 *     )
 * )
 */

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Dapatkan semua produk",
     *     description="Mengembalikan daftar semua produk",
     *     @OA\Response(
     *         response=200,
     *         description="Daftar produk",
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
     *     tags={"Products"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "price"},
     *             @OA\Property(property="name", type="string", example="Product Name"),
     *             @OA\Property(property="price", type="number", format="float", example=19.99),
     *             @OA\Property(property="description", type="string", example="Product Description")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Product created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(response=400, description="Invalid input")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        return response()->json(Product::create($validated), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/products/{product}",
     *     tags={"Products"},
     *     summary="Dapatkan produk berdasarkan ID",
     *     description="Mengembalikan informasi produk",
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="ID produk",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produk ditemukan",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produk tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Product not found")
     *         )
     *     )
     * )
     */
    public function show(Product $product)
    {
        return response()->json($product, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/products/{product}",
     *     summary="Update a product by ID",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="product",
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
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Product updated successfully"),
     *             @OA\Property(property="product", ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Product not found")
     *         )
     *     )
     * )
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric',
            'description' => 'nullable|string',
        ]);

        $product->update($validated);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product,
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{product}",
     *     tags={"Products"},
     *     summary="Hapus produk",
     *     description="Menghapus produk berdasarkan ID",
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="ID produk yang akan dihapus",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Berhasil menghapus produk"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produk tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Product not found")
     *         )
     *     )
     * )
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(null, 204);
    }
}
