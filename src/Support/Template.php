<?php

declare(strict_types=1);

namespace SmartCms\TemplateBuilder\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;

class Template
{
    public Collection $template;

    public VariableTypeRegistry $variableTypeRegistry;

    public bool $isRendered = false;

    public function __construct(VariableTypeRegistry $variableTypeRegistry)
    {
        $this->variableTypeRegistry = $variableTypeRegistry;
        $this->template = collect();
    }

    public function get(): Collection
    {
        return $this->template;
    }

    public function set(Collection $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function render(): string
    {
        if ($this->isRendered) {
            return '';
        }
        $this->isRendered = true;

        return Blade::render($this->getTemplateString(), ['template' => $this->template]);
    }

    private function getTemplateString(): string
    {
        return <<<'blade'
                @foreach ($template as $section)
                @if(!$section->section || $section->section->status === false)
                @continue
                @endif
                    @include($section->section->view_path, $section->section->variables)
                @endforeach
        blade;
    }

    public function provideDefaultVariables(array $expression): array
    {
        $variables = [];
        foreach ($expression as $variable) {
            $rules = explode('|', $variable);
            if (count($rules) < 1) {
                throw new \InvalidArgumentException("Invalid rules for variable $variable");
            }
            $type = explode(':', $rules[0]);
            if (count($type) < 2) {
                throw new \InvalidArgumentException("Invalid rules for variable $variable");
            }
            $name = $type[0];
            $type = $type[1];
            $variables[$name] = $this->variableTypeRegistry->get($type)?->getDefaultValue() ?? null;
        }

        return $variables;
    }
}
