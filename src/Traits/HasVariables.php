<?php

namespace SmartCms\TemplateBuilder\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use SmartCms\TemplateBuilder\Actions\TemplateParser;
use SmartCms\TemplateBuilder\Support\TemplateTypeEnum;

trait HasVariables
{
    abstract public static function getTemplateType(): TemplateTypeEnum;

    public function schema(): Attribute
    {
        return new Attribute(
            get: fn () => TemplateParser::make(static::getTemplateType())->getComponentSchema($this->path)
        );
    }

    public function variables(): Attribute
    {
        return new Attribute(
            get: fn () => TemplateParser::make(static::getTemplateType())->getComponentVariables($this->path, $this->mutateValue($this->value))
        );
    }

    public function mutateValue(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        return $value;
    }

    public function getVariables(mixed $value = []): array
    {
        if (! is_array($value)) {
            $value = [];
        }
        $templateData = $value;
        if (empty($templateData)) {
            $templateData = $this->value ?? [];
        }
        if (! is_array($templateData)) {
            $templateData = [];
        }

        return TemplateParser::make(static::getTemplateType())->getComponentVariables($this->path, $templateData);
    }

    public function viewPath(): Attribute
    {
        return new Attribute(
            get: function () {
                if (! $this->path) {
                    return null;
                }
                $path = str_replace('.blade.php', '', $this->path);

                return str_replace('/', '.', $path);
            }
        );
    }
}
