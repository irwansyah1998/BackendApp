<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="API Documentation",
 *     version="1.0.0",
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
     * @OA\Schema(
     *     schema="Product",
     *     type="object",
     *     @OA\Property(property="id", type="integer", format="int64"),
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="price", type="number", format="float"),
     *     @OA\Property(property="description", type="string"),
     * )
     */

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
     * @OA\Schema(
     *     schema="Product",
     *     type="object",
     *     @OA\Property(property="id", type="integer", format="int64"),
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="price", type="number", format="float"),
     *     @OA\Property(property="description", type="string"),
     * )
     */

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
     * @OA\Schema(
     *     schema="Product",
     *     type="object",
     *     @OA\Property(property="id", type="integer", format="int64"),
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="price", type="number", format="float"),
     *     @OA\Property(property="description", type="string"),
     * )
     */

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric',
            'description' => 'nullable|string',
        ]);

        $product = Product::findOrFail($id);
        $product->update($validated);
        return $product;
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->noContent();
    }
}
