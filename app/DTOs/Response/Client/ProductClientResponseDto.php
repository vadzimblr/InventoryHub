<?php

namespace App\DTOs\Response\Client;

use Spatie\LaravelData\Data;
use App\DTOs\Response\ProductResponseDto;

class ProductClientResponseDto extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $description,
        public readonly float $price,
    ) {}

    /**
     *
     * @param ProductResponseDto $productResponseDto
     * @return self
     */
    public static function fromProductResponseDto(ProductResponseDto $productResponseDto): self
    {
        return new self(
            id: $productResponseDto->id,
            name: $productResponseDto->name,
            description: $productResponseDto->description,
            price: $productResponseDto->price,
        );
    }


    public static function fromProductResponseDtos(array $productResponseDtos): array
    {
        return array_map(fn(array $dto) => ProductClientResponseDto::fromProductResponseDto(ProductResponseDto::fromArray($dto)), $productResponseDtos);
    }
}
