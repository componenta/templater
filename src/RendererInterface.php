<?php

declare(strict_types=1);

namespace Componenta\Templater;

interface RendererInterface
{
    public function render(string $template, array $params = []): string;
}
