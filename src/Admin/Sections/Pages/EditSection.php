<?php

namespace SmartCms\TemplateBuilder\Admin\Sections\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use SmartCms\Support\Admin\Components\Actions\SaveAction;
use SmartCms\Support\Admin\Components\Actions\SaveAndClose;
use SmartCms\TemplateBuilder\Admin\Sections\SectionResource;
use SmartCms\TemplateBuilder\TemplateBuilderPlugin;

class EditSection extends EditRecord
{
    protected static string $resource = SectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\ActionGroup::make([
                SaveAction::make($this),
                SaveAndClose::make($this, SectionResource::getUrl('index')),
                DeleteAction::make(),
            ])->link()->label(__('support::admin.actions'))
                ->icon(\Filament\Support\Icons\Heroicon::ChevronDown)
                ->size(\Filament\Support\Enums\Size::Small)
                ->iconPosition(\Filament\Support\Enums\IconPosition::After)
                ->color('primary'),
        ];
    }

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
}
