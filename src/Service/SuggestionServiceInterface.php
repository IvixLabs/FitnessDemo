<?php

namespace App\Service;

use App\Dto\SuggestionResponseDto;

interface SuggestionServiceInterface
{
    /**
     * @param int         $start
     * @param int         $limit
     * @param string|null $query
     *
     * @return SuggestionResponseDto
     */
    public function __invoke($query = null, $start, $limit);
}
