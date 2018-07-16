<?php

namespace App\Service;

interface MessageRendererServiceInterface
{
    public function renderMessage(string $template, array $params): string;
}