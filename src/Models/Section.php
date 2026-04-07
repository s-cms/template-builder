<?php

namespace SmartCms\TemplateBuilder\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SmartCms\TemplateBuilder\Support\TemplateTypeEnum;
use SmartCms\TemplateBuilder\Traits\HasVariables;
use Spatie\Translatable\HasTranslations;

/**
 * Class TemplateSection
 *
 * @property int $id The unique identifier for the model.
 * @property string $name The name of the template section.
 * @property string $path The path to the template section.
 * @property bool $status The status of the section.
 * @property array $schema The schema configuration for the section.
 * @property array $value The values for the section.
 * @property \DateTime $created_at The date and time when the model was created.
 * @property \DateTime $updated_at The date and time when the model was last updated.
 * @property-read Collection|Template[] $templates The templates using this section.
 */
class Section extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasVariables;

    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean',
        'value' => 'array',
    ];

    protected array $translatable = ['value'];

    public function morphs()
    {
        return $this->morphMany(self::class, 'en');
    }

    public function templates()
    {
        return $this->hasMany(Template::class, 'section_id', 'id');
    }

    public static function getTemplateType(): TemplateTypeEnum
    {
        return TemplateTypeEnum::SECTION;
    }

    public function getTable()
    {
        return config('template-builder.sections_table_name', 'sections');
    }

    public function getFallbackLocale(): string
    {
        return main_lang();
    }
}
