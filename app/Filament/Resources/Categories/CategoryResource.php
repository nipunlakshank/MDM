<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\Categories\Schemas\CategoryForm;
use App\Models\Category;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('code')->label('Code')->searchable()->sortable(),
            TextColumn::make('name')->label('Name')->searchable()->sortable(),
            BadgeColumn::make('status')->label('Status')
                ->colors([
                    'success' => 'Active',
                    'warning' => 'Inactive',
                ])
                ->sortable(),
        ])
            ->defaultPaginationPageOption(5)
            ->filters([
                SelectFilter::make('status')->options([
                    'Active' => 'Active',
                    'Inactive' => 'Inactive',
                ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make()
                    ->requiresConfirmation()
                    ->successNotificationTitle(null)
                    ->action(function ($records) {
                        $user = auth()->user();
                        $deletedCount = 0;
                        $skippedCount = 0;

                        foreach ($records as $record) {
                            if ($user->can('delete', $record)) {
                                $record->delete();
                                $deletedCount++;
                            } else {
                                $skippedCount++;
                            }
                        }

                        // Show notification based on result
                        if ($deletedCount > 0 && $skippedCount > 0) {
                            Notification::make()
                                ->title('Partial Delete')
                                ->body("Deleted {$deletedCount} record(s). {$skippedCount} record(s) were skipped because you don't have permission.")
                                ->warning()
                                ->send();
                        } elseif ($deletedCount > 0) {
                            Notification::make()
                                ->title('Deleted')
                                ->body("Deleted {$deletedCount} record(s).")
                                ->success()
                                ->send();
                        } elseif ($skippedCount > 0) {
                            Notification::make()
                                ->title('No Records Deleted')
                                ->body("You don't have permission to delete any of the selected records.")
                                ->warning()
                                ->send();
                        }
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
