<?php

namespace SmartCms\TemplateBuilder\VariableTypes;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Component;
use SmartCms\TemplateBuilder\Support\VariableTypeInterface;

class ArrayType implements VariableTypeInterface
{
    public static function make(): self
    {
        return new self;
    }

    public static function getName(): string
    {
        return 'array';
    }

    public function getDefaultValue(): mixed
    {
        return [];
    }

    public function getSchema(string $name, ?string $language = null): Field | Component
    {
        return Repeater::make($name)->columns(2);
    }

    public function getValue(mixed $value): mixed
    {
        if (! is_array($value)) {
            return [];
        }

        return array_map(function ($item) {
            return array_map(function ($item) {
                return $item;
            }, $item);
        }, $value);
    }
}
