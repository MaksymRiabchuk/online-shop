<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromoCodeResource\Pages;
use App\Models\PromoCode;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Validation\ValidationException;

class PromoCodeResource extends Resource
{
    protected static ?string $model = PromoCode::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Main info')->collapsible()->schema([
                    TextInput::make('code')->unique(),
                    TextInput::make('percentage')->required()->numeric()->maxValue(100)->minValue(1),
                    Forms\Components\DateTimePicker::make('start_date')->required(),
                    Forms\Components\DateTimePicker::make('end_date')->required(),

                    TextInput::make('max_uses')
                        ->reactive()
                        ->numeric()
                        ->minValue(1)
                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            if ($state > 1) {
                                $set('for_all', 0);
                            }
                        }),

                    Forms\Components\Toggle::make('for_all')
                        ->reactive()
                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            if ($state==1) {
                                $set('max_uses', 1);
                                $set('Users', []);
                            }
                        }),
                ])->columns(2),

                Forms\Components\Section::make('Users')->collapsible()->schema([
                    Forms\Components\Repeater::make('Users')
                        ->relationship('promoCodeUsers')
                        ->schema([
                            Forms\Components\Select::make('user_id')->required()->relationship('user', 'name'),
                        ])->afterStateUpdated(function (Get $get, Set $set, $state) {
                            if ($state) {
                                $set('for_all', 0);
                            }
                        })->default([])
                ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('percentage')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('start_date')->date('d.m.Y')->sortable(),
                Tables\Columns\TextColumn::make('end_date')->date('d.m.Y')->sortable(),
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
            'index' => Pages\ListPromoCodes::route('/'),
            'create' => Pages\CreatePromoCode::route('/create'),
            'edit' => Pages\EditPromoCode::route('/{record}/edit'),
        ];
    }
}
