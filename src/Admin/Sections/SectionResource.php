<?php

namespace SmartCms\TemplateBuilder\Admin\Sections;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use SmartCms\TemplateBuilder\Admin\Sections\Pages\CreateSection;
use SmartCms\TemplateBuilder\Admin\Sections\Pages\EditSection;
use SmartCms\TemplateBuilder\Admin\Sections\Pages\ListSections;
use SmartCms\TemplateBuilder\Admin\Sections\Schemas\SectionForm;
use SmartCms\TemplateBuilder\Admin\Sections\Tables\SectionsTable;
use SmartCms\TemplateBuilder\Models\Section as ModelsSection;
use SmartCms\TemplateBuilder\TemplateBuilderPlugin;

class SectionResource extends Resource
{
    protected static ?string $model = ModelsSection::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static ?int $navigationSort = 1;

    public static function getCluster(): ?string
    {
        return TemplateBuilderPlugin::$cluster ?? null;
    }


    public static function getModelLabel(): string
    {
        return __('template-builder::admin.section');
    }

    public static function getNavigationGroup(): ?string
    {
        return null;
        return __(TemplateBuilderPlugin::$navigationGroup);
    }

    public static function form(Schema $schema): Schema
    {
        return SectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SectionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSections::route('/'),
            // 'create' => CreateSection::route('/create'),
            'edit' => EditSection::route('/{record}/edit'),
        ];
    }
}
