<?php

declare(strict_types=1);

namespace Componenta\Templater;

final readonly class Renderer implements RendererInterface
{
    public function __construct(
        private Engine $engine,
    ) {}

    public function engine(): Engine
    {
        return $this->engine;
    }

    public function render(string $template, array $params = []): string
    {
        return $this->engine->render($template, $params);
    }
}
