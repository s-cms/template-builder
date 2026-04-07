<?php

namespace SmartCms\TemplateBuilder\Support;

use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Component;

interface VariableTypeInterface
{
    public static function make(): self;

    public static function getName(): string;

    public function getDefaultValue(): mixed;

    public function getSchema(string $name, ?string $language = null): Field | Component;

    public function getValue(mixed $value): mixed;
}
