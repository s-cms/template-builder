<?php

namespace SmartCms\TemplateBuilder\Admin\Sections\Schemas;

use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use SmartCms\Support\Admin\Components\Layout\FormGrid;
use SmartCms\Support\Admin\Components\Layout\LeftGrid;
use SmartCms\Support\Admin\Components\Layout\RightGrid;
use SmartCms\TemplateBuilder\Actions\TemplateParser;
use SmartCms\TemplateBuilder\Support\TemplateTypeEnum;

class SectionForm
{
    public static function configure(Schema $schema): Schema
    {
        $components = TemplateParser::make(TemplateTypeEnum::SECTION)->getAll();

        return $schema
            ->components([
                FormGrid::make()->schema([
                    LeftGrid::make()->schema([
                        Section::make()->schema([
                            TextInput::make('name')
                                ->label(__('template-builder::admin.name'))
                                ->required(),
                            Select::make('path')->options($components->pluck('name', 'path')->toArray())->required()->live()->disabledOn('edit'),
                        ])->columns(2)->columnSpan(1),
                        Grid::make(1)
                            ->schema(function (Get $get): array {
                                $path = $get('path');

                                return [
                                    TextEntry::make('section_is_empty')->state(__('template-builder::admin.empty_content'))->hiddenLabel()->hidden(! blank($path)),
                                    ...TemplateParser::make(TemplateTypeEnum::SECTION)->getComponentSchema($path),
                                ];
                            })->live()
                            ->columnSpanFull()->key('dynamicTypeFields'),
                    ]),
                    RightGrid::make()->schema([
                        Section::make('Status')->icon(function (Get $get) {
                            $status = $get('status');
                            return $status == 0 ? Heroicon::OutlinedSun : Heroicon::Sun;
                        })->compact()
                            ->schema([
                                Radio::make('status')->hiddenLabel()
                                    ->reactive()
                                    ->options([
                                        0 => __('template-builder::admin.inactive'),
                                        1 => __('template-builder::admin.active'),

                                    ])->default(1),
                            ]),
                        Section::make(__('template-builder::admin.preview'))->icon(Heroicon::Eye)->compact()->schema([
                            // Action::make(__('template-builder::admin.show'))->disabled()->modal()->modalContent(function (Get $get, Model $record) {
                            //     return str()->of(Blade::render($record->viewPath, $record->variables))->toHtmlString();
                            // }),
                            Text::make('Coming soon'),
                        ])
                    ])->hiddenOn('create'),
                ]),
            ]);
    }
}
