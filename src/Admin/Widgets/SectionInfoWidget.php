<?php

namespace SmartCms\TemplateBuilder\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use SmartCms\TemplateBuilder\Actions\TemplateParser;
use SmartCms\TemplateBuilder\Models\Section;
use SmartCms\TemplateBuilder\Support\TemplateTypeEnum;

class SectionInfoWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function getStats(): array
    {
        $availableTemplates = $this->getAvailableTemplatesCount();
        $usedTemplates = $this->getUsedTemplatesCount();
        $unusedTemplates = $availableTemplates - $usedTemplates;
        $usagePercentage = $availableTemplates > 0 ? round(($usedTemplates / $availableTemplates) * 100, 1) : 0;

        // Generate chart data for the last 7 days of section usage
        $chartData = $this->getUsageChartData();

        return [
            Stat::make(__('template-builder::admin.available_section_templates'), $availableTemplates)
                ->description(__('template-builder::admin.total_section_templates_found'))
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info')
                ->icon('heroicon-o-folder'),

            Stat::make(__('template-builder::admin.used_section_templates'), $usedTemplates)
                ->description(__('template-builder::admin.templates_in_use') . " ({$usagePercentage}%)")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($usedTemplates > 0 ? 'success' : 'gray')
                ->icon('heroicon-o-check-badge')
                ->chart($chartData),

            Stat::make(__('template-builder::admin.unused_section_templates'), $unusedTemplates)
                ->description(__('template-builder::admin.templates_not_used'))
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($unusedTemplates > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-exclamation-triangle'),
        ];
    }

    private function getAvailableTemplatesCount(): int
    {
        return TemplateParser::make(TemplateTypeEnum::SECTION)->getAll()->count();
    }

    private function getUsedTemplatesCount(): int
    {
        return Section::query()->distinct('path')->count('path');
    }

    private function getUsageChartData(): array
    {
        // Get section creation data for the last 7 days
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $count = Section::query()->whereDate('created_at', $date)->count();
            $data[] = $count;
        }

        return $data;
    }
}
