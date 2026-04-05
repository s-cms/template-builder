<?php

namespace SmartCms\TemplateBuilder\Admin\Layouts\Pages;

use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\Size;
use Filament\Support\Icons\Heroicon;
use SmartCms\Support\Admin\Components\Actions\SaveAction;
use SmartCms\Support\Admin\Components\Actions\SaveAndClose;
use SmartCms\TemplateBuilder\Admin\Layouts\LayoutResource;
use SmartCms\TemplateBuilder\TemplateBuilderPlugin;

class EditLayout extends EditRecord
{
    protected static string $resource = LayoutResource::class;

    public function getSubNavigation(): array
    {
        $additionalItems = [];
        if (TemplateBuilderPlugin::$cluster) {
            foreach (TemplateBuilderPlugin::$cluster::getClusteredComponents() as $component) {
                $additionalItems = array_merge($additionalItems, $component::getNavigationItems());
            }
        }

        return array_merge(parent::getSubNavigation(), $additionalItems);
    }

    public static function getCluster(): ?string
    {
        return TemplateBuilderPlugin::$cluster;
    }

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                SaveAction::make($this),
                SaveAndClose::make($this, LayoutResource::getUrl('index')),
                DeleteAction::make(),
            ])->link()->label(__('support::admin.actions'))
                ->icon(Heroicon::ChevronDown)
                ->size(Size::Small)
                ->iconPosition(IconPosition::After)
                ->color('primary'),
        ];
    }
}
