<?php

namespace SmartCms\TemplateBuilder\Admin\Layouts\Pages;

use Filament\Resources\Pages\ListRecords;
use SmartCms\TemplateBuilder\Actions\SyncLayouts;
use SmartCms\TemplateBuilder\Admin\Layouts\LayoutResource;

class ListLayouts extends ListRecords
{
    protected static string $resource = LayoutResource::class;

    public function mount(): void
    {
        parent::mount();
        SyncLayouts::run();
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
