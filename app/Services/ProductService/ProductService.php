<?php

namespace App\Services\ProductService;

use App\DTOs\Request\ProductRequestDto;
use App\DTOs\Response\ProductResponseDto;
use App\Models\Product;
use phpDocumentor\Reflection\Exception;

class ProductService implements Api\ProductServiceInterface
{

    public function storeProduct(ProductRequestDto $productRequestDto): ProductResponseDto
    {
        $product = $productRequestDto->toModel();
        $product->save();
        return ProductResponseDto::fromModel($product);
    }

    public function getProduct(int $productId): ProductResponseDto
    {
        $product = $this->getProductById($productId);
        return ProductResponseDto::fromModel($product);
    }

    public function updateProduct(int $productId, ProductRequestDto $productRequestDto): ProductResponseDto
    {
        $product = $this->getProductById($productId);
        $product->update($productRequestDto->toModel()->toArray());
        return ProductResponseDto::fromModel($product);
    }

    public function updateQuantity(float $quantity, int $productId): ProductResponseDto
    {
        $product = $this->getProductById($productId);
        if($quantity < 0){
            throw new Exception("Quantity must be greater than 0");
        }
        $product->update(['quantity' => $quantity]);
        return ProductResponseDto::fromModel($product);
    }

    public function deleteProduct(int $productId): void
    {
        $product = $this->getProductById($productId);
        $product->delete();
    }
    private function getProductById(int $productId): Product{
        $product = Product::query()
            ->whereNull('deleted_at')
            ->find($productId);

        if (!$product) {
            throw new Exception('Product with id - [' .$product. '] not found');
        }
        return $product;
    }

    public function getAllProducts(): array
    {
        $products = Product::all()->where('deleted_at', '=', null);
        return ProductResponseDto::fromCollection($products);
    }

    public function increaseQuantity(float $quantity, int $productId): void
    {
        if($quantity < 0){
            throw new Exception("Quantity must be greater than 0");
        }
        $product = $this->getProductById($productId);
        $newQuantity = $product->quantity + $quantity;
        $product->update(['quantity' => $newQuantity]);
    }

    public function decreaseQuantity(float $quantity, int $productId): void
    {
        if($quantity < 0){
            throw new Exception("Quantity must be greater than 0");
        }
        $product = $this->getProductById($productId);
        $currentQuantity = $product->quantity;
        if($quantity > $currentQuantity){
            throw new Exception("Quantity must be less than $currentQuantity");
        }
        $newQuantity = $product->quantity - $quantity;
        $product->update(['quantity' => $newQuantity]);
    }
}
