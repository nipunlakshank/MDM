<?php

namespace App\Filament\Resources\Brands;

use App\Filament\Notifications\BulkDeleteNotification;
use App\Filament\Resources\Brands\Pages\CreateBrand;
use App\Filament\Resources\Brands\Pages\EditBrand;
use App\Filament\Resources\Brands\Pages\ListBrands;
use App\Filament\Resources\Brands\Schemas\BrandForm;
use App\Models\Brand;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return BrandForm::configure($schema);
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

                        BulkDeleteNotification::make($deletedCount, $skippedCount);
                    }),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        // $user = auth()->user();
        //
        // if (!$user->isAdmin()) {
        //     $query->where('user_id', $user->id);
        // }

        return $query;
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
            'index' => ListBrands::route('/'),
            'create' => CreateBrand::route('/create'),
            'edit' => EditBrand::route('/{record}/edit'),
        ];
    }
}
