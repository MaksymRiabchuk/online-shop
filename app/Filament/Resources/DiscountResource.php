<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscountResource\Pages;
use App\Filament\Resources\DiscountResource\RelationManagers;
use App\Models\Discount;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Main info')->collapsible()->schema([
                    Forms\Components\Select::make('product_id')->relationship('product', 'name')
                        ->preload()->searchable()->required()->columns(1),
                    TextInput::make('percentage')->required()->numeric()->maxValue(100)->minValue(1),
                    DateTimePicker::make('start_date')->required()->format('Y-m-d H:i:s'),
                    DateTimePicker::make('end_date')->required()->format('Y-m-d H:i:s'),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('percentage')->searchable()->sortable(),
                TextColumn::make('start_date')->dateTime('d.m.Y H:m')->sortable(),
                TextColumn::make('end_date')->dateTime('d.m.Y H:m')->sortable(),
            ])
            ->filters([
                Filter::make('product_id')
                    ->form([
                        Forms\Components\Select::make('product_id')
                            ->label('Product')
                            ->options(function () {
                                return Product::query()
                                    ->whereNotNull('name')
                                    ->select('name', 'id')
                                    ->distinct()
                                    ->orderBy('name', 'asc')
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable()
                            ->placeholder('Choose product')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['product_id'],
                            fn($query, $np) => $query->where('product_id', $np)
                        );
                    }),
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
            'index' => Pages\ListDiscounts::route('/'),
            'create' => Pages\CreateDiscount::route('/create'),
            'edit' => Pages\EditDiscount::route('/{record}/edit'),
        ];
    }
}
