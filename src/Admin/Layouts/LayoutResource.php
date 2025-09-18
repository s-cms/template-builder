<?php

namespace SmartCms\TemplateBuilder\Admin\Layouts;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use SmartCms\TemplateBuilder\Admin\Layouts\Pages\CreateLayout;
use SmartCms\TemplateBuilder\Admin\Layouts\Pages\EditLayout;
use SmartCms\TemplateBuilder\Admin\Layouts\Pages\ListLayouts;
use SmartCms\TemplateBuilder\Admin\Layouts\Schemas\LayoutForm;
use SmartCms\TemplateBuilder\Admin\Layouts\Tables\LayoutsTable;
use SmartCms\TemplateBuilder\Models\Layout;
use SmartCms\TemplateBuilder\TemplateBuilderPlugin;

class LayoutResource extends Resource
{
    protected static ?string $model = Layout::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedSquare3Stack3d;

    protected static ?int $navigationSort = 2;

    public static function getCluster(): ?string
    {
        return TemplateBuilderPlugin::$cluster ?? null;
    }

    public static function getModelLabel(): string
    {
        return __('template-builder::admin.layout');
    }

    public static function getNavigationGroup(): ?string
    {
        return __(TemplateBuilderPlugin::$navigationGroup);
    }

    public static function form(Schema $schema): Schema
    {
        return LayoutForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LayoutsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLayouts::route('/'),
            'create' => CreateLayout::route('/create'),
            'edit' => EditLayout::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }
}
