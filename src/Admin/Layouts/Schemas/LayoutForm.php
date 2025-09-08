<?php

namespace SmartCms\TemplateBuilder\Admin\Layouts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class LayoutForm
{
    public static function configure(Schema $form): Schema
    {
        return $form
            ->components([
                Grid::make()
                    ->gridContainer()
                    ->columns([
                        '@md' => 3,
                        '@xl' => 4,
                    ])
                    ->schema([
                        Grid::make(1)->schema([
                            Section::make()->schema([
                                TextInput::make('name')
                                    ->label(__('template-builder::admin.name'))
                                    ->required(),
                                TextInput::make('path')->disabled(),
                            ])->columns(2),
                            Grid::make(2)
                                ->schema(function () use ($form): array {
                                    $schema = $form->getRecord()?->schema ?? [];

                                    return [
                                        TextEntry::make('layout_is_empty')->state(__('template-builder::admin.empty_content'))->hiddenLabel()->hidden(! blank($schema)),
                                        ...$schema,
                                    ];
                                })->live()
                                ->columnSpanFull()->key('dynamicTypeFields'),
                        ])->columnSpan(3),
                        Section::make(__('template-builder::admin.preview'))->icon(Heroicon::Eye)->compact()->schema([
                            // Action::make(__('template-builder::admin.show'))->disabled()->modal()->modalContent(function (Get $get, Model $record) {
                            //     return str()->of(Blade::render($record->viewPath, $record->variables))->toHtmlString();
                            // }),
                            Text::make('Coming soon'),
                        ]),
                        // ->columnSpanFull(),
                        // Section::make([
                        //     TextEntry::make('created_at')->inlineLabel()->since(),
                        //     TextEntry::make('updated_at')->inlineLabel()->since(),
                        // ])->columnSpan(1)->hiddenOn('create'),
                    ]),
            ])->columns(1);
    }
}
