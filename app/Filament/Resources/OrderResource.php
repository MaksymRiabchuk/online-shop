<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Create or change order')->collapsible()
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Select user (optional)')
                            ->reactive()
                            ->afterStateUpdated(function ($set, $state) {

                                if ($state)
                                {
                                    $user = User::find($state);

                                    if ($user) {
                                        $set('name', $user->name);
                                        $set('lastname', $user->lastname);
                                        $set('email', $user->email);
                                        $set('phone', $user->phone);
                                    }
                                } else {
                                    $set('name', null);
                                    $set('email', null);
                                    $set('lastname', null);
                                    $set('phone', null);
                                }
                            })
                        ->required(),
                        TextInput::make('name')->label('User name')->required(),
                        TextInput::make('lastname')->label('Last name')->required(),
                        TextInput::make('phone')->label('Phone')->required(),
                        TextInput::make('email')->label('Email')->required(),
                        TextInput::make('status')->label('Status')->required(),
                        TextInput::make('comment')->label('Comment')->required(),
                        Forms\Components\Section::make('Select product`s for order')->collapsible()
                            ->schema([
                                Repeater::make('orderProducts')
                                ->schema([
                                    Select::make('product_id')
                                        ->label('Product')
                                        ->options(Product::all()->pluck('name', 'id'))
                                        ->required(),
                                    TextInput::make('quantity')
                                        ->label('Quantity')
                                        ->numeric()
                                        ->default(1)
                                        ->required(),
                                ])
                                    ->reorderable()
                                    ->grid(2)
                            ])
                            ->columns(1),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
