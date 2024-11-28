<?php

namespace App\Services\ProductService\Api;

use App\DTOs\Request\ProductRequestDto;
use App\DTOs\Response\ProductResponseDto;

interface ProductServiceInterface
{
    public function storeProduct(ProductRequestDto $productRequestDto):ProductResponseDto;
    public function getProduct(int $productId): ProductResponseDto;
    public function getAllProducts(): array;
    public function updateProduct(int $productId, ProductRequestDto $productRequestDto):ProductResponseDto;
    public function updateQuantity(float $quantity, int $productId): ProductResponseDto;
    public function deleteProduct(int $productId): void;
    public function increaseQuantity(float $quantity, int $productId): void;
    public function decreaseQuantity(float $quantity, int $productId): void;
}
