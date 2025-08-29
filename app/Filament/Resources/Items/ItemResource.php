<?php

namespace App\Filament\Resources\Items;

use App\Filament\Notifications\BulkDeleteNotification;
use App\Filament\Resources\Items\Pages\CreateItem;
use App\Filament\Resources\Items\Pages\EditItem;
use App\Filament\Resources\Items\Pages\ListItems;
use App\Filament\Resources\Items\Pages\ViewItem;
use App\Filament\Resources\Items\Schemas\ItemInfolist;
use App\Models\Item;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('code')
                ->required()
                ->maxLength(255),
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            Select::make('category_id')
                ->relationship('category', 'name', fn ($query) => $query->where('status', 'Active'))
                ->searchable()
                ->preload()
                ->label('Category'),
            Select::make('brand_id')
                ->relationship('brand', 'name', fn ($query) => $query->where('status', 'Active'))
                ->searchable()
                ->preload()
                ->label('Brand'),
            FileUpload::make('attachment')
                ->image()
                ->directory('items')
                ->imagePreviewHeight('200')
                ->maxSize(2048),
            Select::make('status')
                ->options([
                    'Active' => 'Active',
                    'Inactive' => 'Inactive',
                ])
                ->default('Active')
                ->label('Status'),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ItemInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('code')->label('Code')->searchable()->sortable(),
            TextColumn::make('category.name')->label('Category')->sortable(),
            TextColumn::make('brand.name')->label('Brand')->sortable(),
            TextColumn::make('name')->label('Name')->searchable()->sortable(),
            ImageColumn::make('attachment')->label('Image')->circular(),
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
        $user = auth()->user();

        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

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
            'index' => ListItems::route('/'),
            'create' => CreateItem::route('/create'),
            'view' => ViewItem::route('/{record}'),
            'edit' => EditItem::route('/{record}/edit'),
        ];
    }
}
