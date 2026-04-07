<?php

namespace SmartCms\TemplateBuilder\VariableTypes;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Component;
use SmartCms\TemplateBuilder\Support\VariableTypeInterface;

class HtmlType implements VariableTypeInterface
{
    public static function make(): self
    {
        return new self;
    }

    public static function getName(): string
    {
        return 'html';
    }

    public function getDefaultValue(): mixed
    {
        return $this->mutateString('<b>Default HTML</b>');
    }

    public function getSchema(string $name, ?string $language = null): Field | Component
    {
        return RichEditor::make($name)
            ->columnSpanFull()
            ->toolbarButtons([
                ['bold', 'italic', 'underline', 'strike', 'link'],
                ['h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                ['attachFiles'],
                ['undo', 'redo'],
            ]);
    }

    public function getValue(mixed $value): mixed
    {
        if (is_null($value)) {
            return $this->getDefaultValue();
        }

        return $this->mutateString($value);
    }

    public function mutateString(string $value): string
    {
        return str()->of($value)->toHtmlString();
    }
}
