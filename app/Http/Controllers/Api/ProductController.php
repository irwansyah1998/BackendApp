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
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar produk",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product"))
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    /**
     * Get all products from the database.
     *
     * This method is used to retrieve the full product list for the client app.
     * It returns every product record currently saved in the products table.
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Tambah produk baru",
     *     description="Menyimpan produk baru ke database",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "price"},
     *             @OA\Property(property="name", type="string", example="Keyboard"),
     *             @OA\Property(property="price", type="number", format="float", example=149000),
     *             @OA\Property(property="description", type="string", nullable=true, example="Mechanical keyboard")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produk berhasil dibuat",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    /**
     * Create a new product.
     *
     * This method validates the incoming request, then saves a new product record.
     * It is used for adding new items to the catalog or inventory list.
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
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Lihat detail produk",
     *     description="Mengambil data produk berdasarkan ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID produk",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail produk",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    /**
     * Show one product by its ID.
     *
     * Laravel resolves the product from the route parameter automatically.
     * This is useful when fetching detailed information for a single item.
     */
    public function show(Product $product)
    {
        return response()->json($product, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Update produk",
     *     description="Memperbarui data produk yang sudah ada",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID produk",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Keyboard Updated"),
     *             @OA\Property(property="price", type="number", format="float", example=159000),
     *             @OA\Property(property="description", type="string", nullable=true, example="Updated description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produk berhasil diupdate",
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    /**
     * Update an existing product.
     *
     * This method validates the incoming fields and updates the selected product.
     * It supports partial updates, so only the fields sent by the client are changed.
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
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Hapus produk",
     *     description="Menghapus produk dari database",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID produk",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Produk berhasil dihapus"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    /**
     * Delete a product record.
     *
     * This method removes the selected product from the database.
     * It returns a 204 response to indicate successful deletion with no content.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(null, 204);
    }
}
