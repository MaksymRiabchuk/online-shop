<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('')->collapsible()->schema([
                    TextInput::make('name')
                        ->live(true)
                        ->required(),
                    TextInput::make('lastname')
                        ->live(true)
                        ->required(),
                    TextInput::make('phone')
                        ->live(true)
                        ->required()
                        ->label('Phone Number')
                        ->tel()
                        ->rules(['regex:/^\+?[0-9]{10,15}$/', 'unique:users,phone'])
                        ->helperText('Enter a valid and unique phone number (e.g., +12345678901)'),
                    TextInput::make('email')
                        ->required()
                        ->email()
                        ->afterStateUpdated(function (Set $set, ?string $state) {
                            if ($state && User::where('email', $state)->exists()) {
                                $set('error', 'This email is already taken.');
                            } else {
                                $set('error', null);
                            }
                        })
                        ->live(true)
                        ->label('Email')
                        ->placeholder('Enter user email')
                        ->rules('unique:users,email'),
                    TextInput::make('address')
                        ->live(true)
                        ->required(),
                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->required()
                        ->minLength(8)
                        ->helperText('Password must be at least 8 characters long, include one uppercase letter, one number, and one special character.'),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('lastname')
                    ->label('Lastname')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable()
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
