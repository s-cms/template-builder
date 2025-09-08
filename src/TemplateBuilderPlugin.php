<?php

namespace SmartCms\TemplateBuilder;

use Filament\Contracts\Plugin;
use Filament\Panel;
use SmartCms\TemplateBuilder\Admin\Layouts\LayoutResource;
use SmartCms\TemplateBuilder\Admin\Sections\SectionResource;

class TemplateBuilderPlugin implements Plugin
{
    public static ?string $navigationGroup = null;
    public static ?string $cluster = null;
    public function getId(): string
    {
        return 'template-builder';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            SectionResource::class,
            LayoutResource::class,
        ]);
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(?string $navigationGroup = null, ?string $cluster = null): static
    {
        static::$navigationGroup = $navigationGroup;
        static::$cluster = $cluster;
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
