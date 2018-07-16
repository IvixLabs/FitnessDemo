<?php

namespace App\Dto;

class SuggestionResponseDto
{
    /**
     * @var SuggestionDtoInterface[]
     */
    private $items;

    /**
     * @var int
     */
    private $total;

    /**
     * @param SuggestionDtoInterface[] $items
     * @param int                      $total
     */
    public function __construct(array $items, int $total)
    {
        $this->items = $items;
        $this->total = $total;
    }

    /**
     * @return SuggestionDtoInterface[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }
}
