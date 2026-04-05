<?php

namespace SmartCms\TemplateBuilder\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use SmartCms\TemplateBuilder\Models\Template;

/**
 * Trait HasTemplate
 */
trait HasTemplate
{
    /**
     * Get the template relationship.
     *
     * @return MorphOne
     */
    public function template()
    {
        return $this->morphOne(Template::class, 'entity');
    }
}
