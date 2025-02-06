<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Оновлюємо основні дані замовлення
        $order = $this->record; // Отримуємо поточний запис
        $order->update(collect($data)->except(['orderProducts'])->toArray());

        // Оновлюємо продукти замовлення
        if (isset($data['orderProducts']) && is_array($data['orderProducts'])) {
            $order->orderProducts()->delete(); // Видаляємо старі продукти
            foreach ($data['orderProducts'] as $orderProductData) {
                $order->orderProducts()->create($orderProductData);
            }
        }

        return $data;
    }
}
