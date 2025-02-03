<?php

namespace App\Filament\Resources;

use App\Enums\ProductReviewStatuses;
use App\Filament\Resources\ProductReviewResource\Pages;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductReviewResource extends Resource
{
    protected static ?string $model = ProductReview::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Main info')->collapsible()->schema([
                    Forms\Components\Select::make('user_id')->relationship('user', 'name')
                        ->preload()->searchable()->required()->label('User')->columnSpan(2)->disabled(),
                    Forms\Components\Select::make('product_id')->relationship('product', 'name')
                        ->preload()->searchable()->required()->label('Product')->columnSpan(2)->disabled(),
                    Forms\Components\TextInput::make('title')->label('Title')
                        ->columnSpan(2)->disabled(),
                    Forms\Components\TextInput::make('rate')->numeric()->label('Rate')
                        ->columnSpan(1)->disabled(),
                    Forms\Components\Select::make('status')->options(ProductReviewStatuses::listData())
                        ->label('Status')->columnSpan(1),
                    Forms\Components\Textarea::make('review')->label('Review')->disabled()->columnSpan(4),
                ])->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Title'),
                Tables\Columns\TextColumn::make('user.name')->label('User')->searchable()
                    ->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('product.name')->label('Product')->searchable()
                    ->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('review')->html()->label('Review')->toggleable()->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('status')
                    ->state(function (ProductReview $record): string {
                        return $record->status ? ProductReviewStatuses::getLabel($record->status) : ' ';
                    })->label('Status')->toggleable()->sortable(),
            ])->defaultSort('updated_at','desc')
            ->filters([
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
            'index' => Pages\ListProductReviews::route('/'),
//            'create' => Pages\CreateProductReview::route('/create'),
            'edit' => Pages\EditProductReview::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
