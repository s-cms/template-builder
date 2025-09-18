<?php

namespace SmartCms\TemplateBuilder\Support;

use Filament\Actions\Action;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use SmartCms\TemplateBuilder\VariableTypes\ArrayType;
use SmartCms\TemplateBuilder\VariableTypes\BoolType;
use SmartCms\TemplateBuilder\VariableTypes\HtmlType;
use SmartCms\TemplateBuilder\VariableTypes\TextType;

class VariableTypeRegistry
{
    protected array $types = [];

    public bool $shouldThrowError = true;

    public function __construct()
    {
        $this->register(TextType::class);
        $this->register(BoolType::class);
        $this->register(ArrayType::class);
        $this->register(HtmlType::class);
    }

    public function register(string $class): void
    {
        if (! is_subclass_of($class, VariableTypeInterface::class)) {
            throw new InvalidArgumentException("Class $class must implement VariableTypeInterface");
        }

        $this->types[$class::getName()] = $class;
    }

    public function get(string $name): ?VariableTypeInterface
    {
        if (! isset($this->types[$name])) {
            return null;
        }

        return app($this->types[$name]);
    }

    public function all(): array
    {
        return $this->types;
    }

    public function getSchema(string $name, string $type, string $validation = '', string $prefix = 'value.', $fullSchema = []): ?Section
    {
        $result = Section::make($this->getLabelFromName($name))
            ->compact();
        if (str_contains($name, '.')) {
            return null;
        }
        $type = $this->get($type);
        if (! $type) {
            if ($this->shouldThrowError) {
                throw new \InvalidArgumentException("Invalid type for variable $name");
            }

            return null;
        }
        $isRequired = str_contains($validation, 'required');
        $component = $type->getSchema($prefix . $name);
        if ($component instanceof Field) {
            $component->required($isRequired)
                ->hiddenLabel()
                ->rules($validation);
        }
        if ($type instanceof ArrayType) {
            $arraySchema = [];
            foreach ($fullSchema as $key => $value) {
                if (str_contains($key, $name . '.*') && $key !== $name) {
                    $rules = explode('|', $fullSchema[$key]);
                    $validation = array_slice($rules, 1);
                    $keyName = explode('.', $key);
                    $keyName = end($keyName);
                    $arraySchema[] = $this->getSchema($keyName, $rules[0], implode('|', $validation), '', $fullSchema);
                }
            }
            $component->schema($arraySchema);
        }
        //  else {
        //     $result->headerActions([$this->getTranslateAction($name, $component)]);
        // }
        $result->schema([
            // ...$this->getTranslateFields($prefix . $name),
            $component,
        ]);
        // if ($type instanceof ArrayType) {
        $result->columnSpanFull();

        // }
        return $result;
    }

    public function getVariable(string $name, string $type, mixed $value, array $fullSchema): mixed
    {
        $type = $this->get($type);
        if (! $type) {
            if ($this->shouldThrowError) {
                throw new \InvalidArgumentException("Invalid type for variable $name");
            }

            return null;
        }
        if ($type instanceof ArrayType) {
            $arrayValues = [];
            $arraySchema = [];
            foreach ($fullSchema as $key => $item) {
                if (str_contains($key, $name . '.*') && $key !== $name) {
                    $nestedName = $this->getNestedName($key);
                    $nestedType = $this->getNestedType($fullSchema[$key]);
                    $arraySchema[$nestedName] = $nestedType;
                }
            }
            if (! is_array($value)) {
                return [];
            }
            foreach ($value as $array_item) {
                $itemValues = [];
                foreach ($arraySchema as $variable_name => $variable_type) {
                    $variableTypeValue = $array_item[$variable_name] ?? null;
                    $itemValues[$variable_name] = $this->getVariable($variable_name, $variable_type, $variableTypeValue, $fullSchema);
                }
                $arrayValues[] = $itemValues;
            }

            return $arrayValues;
        }
        if (is_null($value)) {
            return $type->getDefaultValue();
        }

        try {
            return $type->getValue($value);
        } catch (\Exception $e) {
            Log::debug('Error getting value for variable', [
                'name' => $name,
                'type' => $type::getName(),
                'value' => $value,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $type->getDefaultValue();
        }
    }

    public function getLabelFromName(string $name): string
    {
        return str($name)->replace('_', ' ')->ucfirst()->toString();
    }

    public function getTranslateAction(string $name, Field | Component $component): ?Action
    {
        /** @phpstan-ignore-next-line */
        if ($component instanceof ArrayType || ! $component instanceof Field || $component instanceof BaseFileUpload) {
            return null;
        }

        return Action::make('translate_' . $name)
            ->icon(function (): string {
                return 'heroicon-o-language';
            })
            ->label(__('template-builder::admin.translate'))
            ->iconButton()
            ->mountUsing(function (array $data, Get $get, Schema $form) use ($component) {
                $data = [];
                foreach (app('lang')->adminLanguages() as $lang) {
                    $data[$lang->slug] = $get($this->getComponentLanguageName($component->getName(), $lang->slug));
                }
                $form->fill($data);
            })
            ->schema(function (Schema $schema) use ($component) {
                $schemas = app('lang')->adminLanguages()->map(function ($lang) use ($component) {
                    $componentCopy = clone $component;

                    return Section::make($this->getLabelFromName($lang->name))
                        ->compact()->schema([
                            $componentCopy->statePath($lang->slug)->name($lang->slug),
                        ]);
                });

                return $schema->schema($schemas->toArray());
            })
            ->action(function (array $data, Set $set, $get) use ($component) {
                foreach ($data as $key => $value) {
                    $set($this->getComponentLanguageName($component->getName(), $key), $value);
                }
            })
            ->hidden(function () {
                return app('lang')->adminLanguages()->count() <= 1;
            });
    }

    public function getTranslateFields(string $name): array
    {
        return app('lang')->adminLanguages()->map(function ($lang) use ($name) {
            return Hidden::make($this->getComponentLanguageName($name, $lang->slug));
        })->toArray();
    }

    public function getComponentLanguageName(string $name, string $lang): string
    {
        $nameArray = explode('.', $name);
        if (count($nameArray) == 1) {
            return $lang . '.' . $name;
        }
        array_splice($nameArray, 1, 0, $lang);

        return implode('.', $nameArray);
    }

    public function getNestedName(string $name): string
    {
        $nameArray = explode('.', $name);
        if (count($nameArray) == 1) {
            return $name;
        }

        return end($nameArray);
    }

    public function getNestedType(string $rules): string
    {
        $rules = explode('|', $rules);

        return $rules[0];
    }
}
