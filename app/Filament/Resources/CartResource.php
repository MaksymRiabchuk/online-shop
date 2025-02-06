<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CartResource\Pages;
use App\Filament\Resources\CartResource\RelationManagers;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CartResource extends Resource
{
    protected static ?string $model = Cart::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Main info')->collapsible()->schema([
                    Forms\Components\Select::make('user_id')->relationship('user', 'name')
                        ->preload()->searchable()->required()->label('User'),
                    Forms\Components\Select::make('product_id')->relationship('product', 'name')
                        ->preload()->searchable()->required()->label('Product'),
                    Forms\Components\TextInput::make('quantity')->numeric()->required()->label('Quantity'),
                    Forms\Components\TextInput::make('cost')->numeric()->required()->label('Cost'),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('User')->sortable()->searchable(),
                TextColumn::make('product.name')->label('Product')->sortable()->searchable(),
                TextColumn::make('quantity')->label('Quantity')->sortable(),
                TextColumn::make('cost')->label('Cost')->sortable(),
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
                Filter::make('user_id')
                    ->form([
                        Forms\Components\Select::make('user_id')
                            ->label('User')
                            ->options(function () {
                                return User::query()
                                    ->whereNotNull('name')
                                    ->select('name', 'id')
                                    ->distinct()
                                    ->orderBy('name', 'asc')
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable()
                            ->placeholder('Choose user')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['user_id'],
                            fn($query, $np) => $query->where('user_id', $np)
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
            'index' => Pages\ListCarts::route('/'),
            'create' => Pages\CreateCart::route('/create'),
            'edit' => Pages\EditCart::route('/{record}/edit'),
        ];
    }
}
