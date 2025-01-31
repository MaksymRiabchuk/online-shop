<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderProductResource\Pages;
use App\Filament\Resources\OrderProductResource\RelationManagers;
use App\Models\OrderProduct;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderProductResource extends Resource
{
    protected static ?string $model = OrderProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Modify order products')->collapsible()
                    ->schema([
                        TextInput::make('order_id')->required(),
                        TextInput::make('product_id')->required(),
                        TextInput::make('quantity')->required(),
                        TextInput::make('cost')->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')->label('Order ID')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('product_id')->label('Product ID')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('quantity')->label('Quantity')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('cost')->label('Summery cost')->searchable()->sortable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListOrderProducts::route('/'),
            'create' => Pages\CreateOrderProduct::route('/create'),
            'edit' => Pages\EditOrderProduct::route('/{record}/edit'),
        ];
    }
}
