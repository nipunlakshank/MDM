<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Select::make('status')
                    ->options(['Active' => 'Active', 'Inactive' => 'Inactive'])
                    ->default('Active')
                    ->required(),
            ]);
    }
}
