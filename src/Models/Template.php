<?php

namespace SmartCms\TemplateBuilder\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Template
 *
 * @property int $id The unique identifier for the model.
 * @property int $template_section_id The template section identifier.
 * @property int $sorting The sorting order of the template.
 * @property bool $status The status of the template.
 * @property array|null $value The template values.
 * @property string $entity_type The type of entity this template belongs to.
 * @property int $entity_id The ID of the entity this template belongs to.
 * @property \DateTime $created_at The date and time when the model was created.
 * @property \DateTime $updated_at The date and time when the model was last updated.
 * @property-read Section $section The template section.
 * @property-read mixed $entity The entity this template belongs to.
 */
class Template extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'value' => 'array',
    ];

    public function entity()
    {
        return $this->morphTo();
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function getTable()
    {
        return config('template-builder.templates_table_name', 'templates');
    }
}
