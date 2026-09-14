<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\PathItem(
 *     path="/api/products",
 *     @OA\Get(
 *         path="/api/products",
 *         tags={"Products"},
 *         summary="Get all products",
 *         description="Return a list of all available products.",
 *         security={{"bearerAuth": {}}},
 *         @OA\Response(
 *             response=200,
 *             description="Products retrieved successfully",
 *             @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product"))
 *         ),
 *         @OA\Response(response=401, description="Unauthorized")
 *     ),
 *     @OA\Post(
 *         path="/api/products",
 *         tags={"Products"},
 *         summary="Create a new product",
 *         description="Create and store a new product record.",
 *         security={{"bearerAuth": {}}},
 *         @OA\RequestBody(
 *             required=true,
 *             @OA\JsonContent(
 *                 required={"name", "price"},
 *                 @OA\Property(property="name", type="string", example="Keyboard"),
 *                 @OA\Property(property="price", type="number", format="float", example=149000),
 *                 @OA\Property(property="description", type="string", nullable=true, example="Mechanical keyboard")
 *             )
 *         ),
 *         @OA\Response(
 *             response=201,
 *             description="Product created successfully",
 *             @OA\JsonContent(ref="#/components/schemas/Product")
 *         ),
 *         @OA\Response(response=401, description="Unauthorized")
 *     )
 * )
 *
 * @OA\PathItem(
 *     path="/api/products/{id}",
 *     @OA\Get(
 *         path="/api/products/{id}",
 *         tags={"Products"},
 *         summary="Get product by ID",
 *         description="Retrieve a single product by its identifier.",
 *         security={{"bearerAuth": {}}},
 *         @OA\Parameter(
 *             name="id",
 *             in="path",
 *             required=true,
 *             description="Product ID",
 *             @OA\Schema(type="integer")
 *         ),
 *         @OA\Response(
 *             response=200,
 *             description="Product retrieved successfully",
 *             @OA\JsonContent(ref="#/components/schemas/Product")
 *         ),
 *         @OA\Response(response=401, description="Unauthorized")
 *     ),
 *     @OA\Put(
 *         path="/api/products/{id}",
 *         tags={"Products"},
 *         summary="Update a product",
 *         description="Update an existing product record.",
 *         security={{"bearerAuth": {}}},
 *         @OA\Parameter(
 *             name="id",
 *             in="path",
 *             required=true,
 *             description="Product ID",
 *             @OA\Schema(type="integer")
 *         ),
 *         @OA\RequestBody(
 *             required=true,
 *             @OA\JsonContent(
 *                 @OA\Property(property="name", type="string", example="Keyboard Updated"),
 *                 @OA\Property(property="price", type="number", format="float", example=159000),
 *                 @OA\Property(property="description", type="string", nullable=true, example="Updated description")
 *             )
 *         ),
 *         @OA\Response(response=200, description="Product updated successfully"),
 *         @OA\Response(response=401, description="Unauthorized")
 *     ),
 *     @OA\Delete(
 *         path="/api/products/{id}",
 *         tags={"Products"},
 *         summary="Delete a product",
 *         description="Delete a product record from the database.",
 *         security={{"bearerAuth": {}}},
 *         @OA\Parameter(
 *             name="id",
 *             in="path",
 *             required=true,
 *             description="Product ID",
 *             @OA\Schema(type="integer")
 *         ),
 *         @OA\Response(response=204, description="Product deleted successfully"),
 *         @OA\Response(response=401, description="Unauthorized")
 *     )
 * )
 */
class ProductController extends Controller
{
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
