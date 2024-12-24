<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\ProductImage;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class ManageProductImages extends ManageRelatedRecords
{
    protected static string $resource = ProductResource::class;

    protected static string $relationship = 'images';

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    public function getTitle(): string | Htmlable
    {
        return "Редагування зображень товару {$this->getRecordTitle()}";
    }

    public static function getNavigationLabel(): string
    {
        return 'Зображення товару';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('is_main')->label('Головна')->default(0),
            ])
            ->columns(2);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\ImageColumn::make('image')->label('Зображення'),
            ])->reorderable('order')->defaultSort('order')
            ->filters([ ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->groupedBulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }


}
