# Componenta Templater

Template rendering contracts and a League Plates adapter for Componenta. The package provides a small renderer abstraction while keeping application bootstrapping in `componenta/templater-app`.

## Installation

```bash
composer require componenta/templater
```

## Main API

`RendererInterface` is the public rendering contract:

```php
interface RendererInterface
{
    public function render(string $template, array $params = []): string;
}
```

`Renderer` implements the contract with a required `Engine` instance. `Engine` extends `League\Plates\Engine` and adds a configurable template factory hook.

| Type | Responsibility |
|---|---|
| `RendererInterface` | Stable contract for rendering a template name with parameters. |
| `Renderer` | Thin adapter around `Engine`; exposes `engine()` when callers need direct Plates access. |
| `Engine` | Plates engine subclass with an optional template factory hook. |

`Engine` keeps the normal Plates constructor shape:

```php
new Engine(
    directory: __DIR__ . '/templates',
    fileExtension: 'phtml',
    templateFactory: null,
);
```

The third argument can be any callable with the shape `callable(Engine $engine, string $name): League\Plates\Template\Template`. You can also install it later:

```php
$engine->setTemplateFactory(
    static fn (Engine $engine, string $name) => $engine->getTemplate($name),
);
```

## Quick Start

```php
use Componenta\Templater\Engine;
use Componenta\Templater\Renderer;

$renderer = new Renderer(new Engine(__DIR__ . '/templates', 'phtml'));

echo $renderer->render('welcome', ['name' => 'Componenta']);
```

Use `RendererInterface` in application services. Use `Renderer::engine()` only when a caller must add folders, register functions, or call Plates-specific APIs.

## Boundary

This package does not register container services, does not resolve template paths, and does not define the global `view()` helper. Use `componenta/templater-app` for application integration.
