<?php

namespace App\Service\MessageRenderer;

use App\Service\MessageRendererServiceInterface;

class MustacheMessageRendererService implements MessageRendererServiceInterface
{

    public function renderMessage(string $template, array $params) : string
    {
        $m = new \Mustache_Engine();
        return $m->render($template, $params);
    }
}