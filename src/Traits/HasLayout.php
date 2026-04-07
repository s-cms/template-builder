<?php

namespace SmartCms\TemplateBuilder\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use SmartCms\TemplateBuilder\Actions\TemplateParser;
use SmartCms\TemplateBuilder\Models\Layout;
use SmartCms\TemplateBuilder\Support\TemplateTypeEnum;

/**
 * Trait HasLayout
 */
trait HasLayout
{
    public function initializeHasTemplate(): void
    {
        $this->mergeCasts(['layout_settings' => 'array']);
    }

    protected static function bootHasTemplate(): void
    {
        static::booting(function (Model $model) {
            $model->mergeCasts(['layout_settings' => 'array']);
        });
    }

    /**
     * Get the template relationship.
     *
     * @return MorphOne
     */
    public function layout()
    {
        return $this->belongsTo(Layout::class, 'layout_id');
    }

    public function layoutVariables(): Attribute
    {
        return Attribute::make(
            get: fn () => TemplateParser::make(TemplateTypeEnum::LAYOUT)->getComponentVariables($this?->layout?->path, $this->layout_settings),
        );
    }
}
