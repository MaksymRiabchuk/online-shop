<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyProductResource\Pages;
use App\Filament\Resources\PropertyProductResource\RelationManagers;
use App\Models\Property;
use App\Models\PropertyProduct;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;

class PropertyProductResource extends Resource
{
    protected static ?string $model = PropertyProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Fetch the properties for a specific product')->collapsible()
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->relationship('product', 'name')
                            ->required(),
                        Forms\Components\Select::make('property_id')
                            ->label('Select Property')
                            ->options(function () {

                                return Property::all()->pluck('name', 'id')->filter(function ($value) {
                                    return !is_null($value);
                                });
                            })
                            ->reactive()
                            ->afterStateUpdated(function ($set, $state) {

                                $set('property_value_id', null);
                            })
                            ->required(),
                        Forms\Components\Select::make('property_value_id')
                            ->label('Select Property Value')
                            ->options(function (callable $get) {
                                $propertyId = $get('property_id');

                                if ($propertyId) {
                                    $property = Property::find($propertyId);
                                    if ($property) {
                                        return $property->propertyValues
                                            ->pluck('value', 'id')
                                            ->filter(function ($value) {
                                                return !is_null($value);
                                            });
                                    }
                                }
                                return [];
                            })
                            ->required()
                            ->disabled(fn($get) => !$get('property_id')),
                    ])
                    ->columns(3)
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')->label('Product name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('property.name')->label('Property name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('propertyValue.value')->label('Property Value')->searchable()->sortable(),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPropertyProducts::route('/'),
            'create' => Pages\CreatePropertyProduct::route('/create'),
            'edit' => Pages\EditPropertyProduct::route('/{record}/edit'),
        ];
    }
}
