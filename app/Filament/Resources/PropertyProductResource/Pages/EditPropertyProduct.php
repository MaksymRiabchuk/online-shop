<?php

namespace App\Filament\Resources\PropertyProductResource\Pages;

use App\Filament\Resources\PropertyProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPropertyProduct extends EditRecord
{
    protected static string $resource = PropertyProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
