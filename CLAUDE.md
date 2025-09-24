# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel package called `smart-cms/template-builder` that provides a template building system for CMS applications. It allows creating and managing layouts, sections, and dynamic templates with variable types and Blade directives.

## Development Commands

### Testing
```bash
composer test
# or
vendor/bin/pest
```

### Test Coverage
```bash
composer test-coverage
# or
vendor/bin/pest --coverage
```

### Code Analysis
```bash
composer analyse
# or
phpstan analyse --memory-limit 1G -c phpstan.neon.dist
```

### Code Formatting
```bash
composer format
# or
vendor/bin/pint
```

## Architecture

### Core Components

**Template System**
- `SmartCms\TemplateBuilder\Support\Template`: Core template rendering class that processes collections of sections and renders them via Blade
- `SmartCms\TemplateBuilder\Actions\TemplateParser`: Parses template files to extract names, schemas, and variable definitions
- Custom Blade directives: `@template`, `@schema`, `@name` for template rendering and variable handling

**Variable Type System**
- `SmartCms\TemplateBuilder\Support\VariableTypeRegistry`: Central registry for managing variable types (text, bool, array, html, image)
- `SmartCms\TemplateBuilder\Support\VariableTypeInterface`: Interface that all variable types must implement
- Built-in types in `src/VariableTypes/`: TextType, BoolType, ArrayType, HtmlType, ImageType

**Models & Database**
- `Models\Layout`: Main layout entities
- `Models\Section`: Template sections with variables
- `Models\Template`: Template instances
- Migrations: `create_layouts_table`, `create_sections_table`, `create_templates_table`

**Admin Interface** (Filament-based)
- Layout management: `src/Admin/Layouts/`
- Section management: `src/Admin/Sections/`
- Forms, tables, and pages for CRUD operations
- `TemplateBuilderPlugin`: Main Filament plugin class

**Commands**
- `MakeLayoutCommand`: Generate new layout files
- `MakeSectionCommand`: Generate new section files
- `MakeVariableTypeCommand`: Generate new variable type classes

### Key Features

- **Multilingual Support**: Integration with `smart-cms/lang` for translatable variables
- **Dynamic Schema Parsing**: Automatic extraction of variable schemas from Blade template comments
- **Filament Integration**: Admin interface built on Filament v4
- **Variable Type Extensibility**: Plugin system for custom variable types
- **Template Inheritance**: Layout and section composition system

### Dependencies

- Laravel/PHP 8.3+
- Filament v4.0+
- Spatie Laravel Package Tools
- Spatie Laravel Translatable
- SmartCMS packages: `smart-cms/lang`, `smart-cms/support`

### File Structure Patterns

- Admin interfaces follow Filament resource patterns
- Variable types implement `VariableTypeInterface`
- Actions follow single-responsibility pattern
- Models use traits for shared functionality (`HasTemplate`, `HasLayout`, `HasVariables`)