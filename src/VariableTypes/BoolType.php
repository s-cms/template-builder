<?php

namespace SmartCms\TemplateBuilder\VariableTypes;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use SmartCms\TemplateBuilder\Support\VariableTypeInterface;

class BoolType implements VariableTypeInterface
{
    public static function make(): self
    {
        return new self;
    }

    public static function getName(): string
    {
        return 'bool';
    }

    public function getDefaultValue(): mixed
    {
        return false;
    }

    public function getSchema(string $name, ?string $language = null): Field | Component
    {
        return Toggle::make($name);
    }

    public function getValue(mixed $value): mixed
    {
        return $value ?? false;
    }
}
