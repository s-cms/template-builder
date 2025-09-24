<?php

namespace SmartCms\TemplateBuilder\Admin\Sections\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use SmartCms\TemplateBuilder\Models\Section;

class SectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort(function (Builder $query) {
                return $query->orderByRaw('name,path,created_at desc');
            })
            ->columns([
                TextColumn::make('name')->label(__('template-builder::admin.name'))->searchable(),
                ToggleColumn::make('status')->label(__('template-builder::admin.status')),
                TextColumn::make('used_times')->getStateUsing(function (Section $record) {
                    return $record->templates()->count();
                })->label(__('template-builder::admin.used_times'))->badge()->color(function ($state) {
                    return $state > 0 ? 'success' : 'gray';
                })->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('created_at')->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('updated_at')->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    true => __('template-builder::admin.active'),
                    false => __('template-builder::admin.inactive'),
                ]),
            ])
            ->recordActions([])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
