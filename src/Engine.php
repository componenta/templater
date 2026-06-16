<?php

declare(strict_types=1);

namespace Componenta\Templater;

use League\Plates\Template\Template;

class Engine extends \League\Plates\Engine
{
    /** @var null|callable(self, string): Template */
    private mixed $templateFactory = null;

    public function __construct(
        ?string $directory = null,
        string $fileExtension = 'phtml',
        ?callable $templateFactory = null,
    ) {
        parent::__construct($directory, $fileExtension);

        if ($templateFactory !== null) {
            $this->setTemplateFactory($templateFactory);
        }
    }

    public function setTemplateFactory(callable $factory): void
    {
        $this->templateFactory = static fn(self $engine, string $name): Template => $factory($engine, $name);
    }

    public function make($name, array $data = []): Template
    {
        return $this->templateFactory !== null
            ? ($this->templateFactory)($this, $name)
            : parent::make($name);
    }
}
