<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Зберігаємо основні дані замовлення
        $orderData = collect($data)->except(['orderProducts'])->toArray();
        $order = Order::create($orderData);

        // Зберігаємо продукти замовлення
        if (isset($data['orderProducts']) && is_array($data['orderProducts'])) {
            foreach ($data['orderProducts'] as $orderProductData) {
                $order->orderProducts()->create($orderProductData);
            }
        }

        return $data;
    }
}
