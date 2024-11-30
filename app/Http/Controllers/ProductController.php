<?php

namespace App\Http\Controllers;

use App\DTOs\Request\ProductRequestDto;
use App\DTOs\Response\Client\ProductClientResponseDto;
use App\Services\ProductService\Api\ProductServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductServiceInterface $productService,
    ){}

    public function showAllProductsForClients(Request $request): JsonResponse
    {
        $products = $this->productService->getAllProducts();
        $products = ProductClientResponseDto::fromProductResponseDtos($products);
        return response()->json($products,200);
    }
    public function showAllProducts(Request $request): JsonResponse
    {
        $products = $this->productService->getAllProducts();
        return response()->json($products,200);
    }

    public function createProduct(Request $request): JsonResponse{
        $productDetails = $request->all();
        $productDetails['createdBy'] = Auth::user()->id;
        $productRequestDto = ProductRequestDto::fromArray($productDetails);
        $responseContent = $this->productService->storeProduct($productRequestDto);
        return response()->json($responseContent,201);
    }
    public function updateProduct(Request $request, int $id): JsonResponse {
        $productRequestDto = ProductRequestDto::fromArray($request->all());
        $responseContent = $this->productService->updateProduct($id,$productRequestDto);
        return response()->json($responseContent,200);
    }
    public function showProduct(Request $request, int $id): JsonResponse{
        $product = $this->productService->getProduct($id);
        return response()->json($product,200);
    }
    public function addStock(Request $request,int $id, int $amount): JsonResponse{
        $this->productService->increaseQuantity($amount,$id);
        return response()->json(null,201);
    }
    public function removeStock(Request $request, int $id, int $amount): JsonResponse{
        $this->productService->decreaseQuantity($amount,$id);
        return response()->json(null,201);
    }
    public function getStockOfProduct(Request $request, int $id): JsonResponse{
        $stock = $this->productService->getStockOfProduct($id);
        return response()->json(["stock"=>$stock],200);
    }
}
