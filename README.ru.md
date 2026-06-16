# Componenta Templater

Контракты рендеринга шаблонов и адаптер League Plates для Componenta. Пакет дает небольшую абстракцию рендерера, а загрузка приложения находится в `componenta/templater-app`.

## Установка

```bash
composer require componenta/templater
```

## Основной API

`RendererInterface` является публичным контрактом рендеринга:

```php
interface RendererInterface
{
    public function render(string $template, array $params = []): string;
}
```

`Renderer` реализует контракт через обязательный экземпляр `Engine`. `Engine` расширяет `League\Plates\Engine` и добавляет настраиваемую фабрику шаблонов.

| Тип | За что отвечает |
|---|---|
| `RendererInterface` | Стабильный контракт рендеринга шаблона по имени и параметрам. |
| `Renderer` | Тонкий адаптер над `Engine`; метод `engine()` дает доступ к Plates, когда он действительно нужен. |
| `Engine` | Наследник движка Plates с опциональной фабрикой шаблонов. |

`Engine` сохраняет обычную форму конструктора Plates:

```php
new Engine(
    directory: __DIR__ . '/templates',
    fileExtension: 'phtml',
    templateFactory: null,
);
```

Третий аргумент может быть callable формы `callable(Engine $engine, string $name): League\Plates\Template\Template`. Фабрику можно установить и позже:

```php
$engine->setTemplateFactory(
    static fn (Engine $engine, string $name) => $engine->getTemplate($name),
);
```

## Быстрый старт

```php
use Componenta\Templater\Engine;
use Componenta\Templater\Renderer;

$renderer = new Renderer(new Engine(__DIR__ . '/templates', 'phtml'));

echo $renderer->render('welcome', ['name' => 'Componenta']);
```

В сервисах приложения зависите от `RendererInterface`. `Renderer::engine()` используйте только там, где нужно добавить папки, зарегистрировать функции или вызвать API Plates напрямую.

## Граница пакета

Пакет не регистрирует сервисы контейнера, не разрешает пути к шаблонам и не определяет глобальную вспомогательную функцию `view()`. Для интеграции с приложением используйте `componenta/templater-app`.
