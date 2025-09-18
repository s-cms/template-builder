<?php

namespace SmartCms\TemplateBuilder\Admin\Sections\Pages;

use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use SmartCms\TemplateBuilder\Actions\TemplateParser;
use SmartCms\TemplateBuilder\Admin\Sections\SectionResource;
use SmartCms\TemplateBuilder\Admin\Widgets\SectionInfoWidget;
use SmartCms\TemplateBuilder\Support\TemplateTypeEnum;

class ListSections extends ListRecords
{
    protected static string $resource = SectionResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            SectionInfoWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            // temp fix, because after filament update livewire doesnt handle dynamic variables in schema
            CreateAction::make()->modal()->schema(function (): array {
                $components = TemplateParser::make(TemplateTypeEnum::SECTION)->getAll();

                return [
                    TextInput::make('name')->required(),
                    Select::make('path')
                        ->label(__('template-builder::admin.template_path'))
                        ->options(
                            $components
                                ->pluck('name', 'path')
                                ->toArray()
                        )
                        ->required()
                        ->live(),
                ];
            }),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
