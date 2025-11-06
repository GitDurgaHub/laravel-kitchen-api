<?php

namespace App\Services;

use App\Models\Order;

class KitchenCapacityService
{
    private int $capacity;
    private int $slotSeconds;

    public function __construct(
        int $capacity = 0,
        int $slotSeconds = 300,
    ) {
        $this->capacity = $capacity ?: (int) config('app.kitchen_capacity', 5);
        $this->slotSeconds = $slotSeconds ?: (int) config('app.suggestion_slot_seconds', 300);
    }

    public function activeCount(): int
    {
        return Order::query()->where('status', 'active')->count();
    }

    public function hasCapacity(): bool
    {
        return $this->activeCount() < $this->capacity;
    }

    public function capacity(): int
    {
        return $this->capacity;
    }

    public function suggestNextPickup(\DateTimeInterface $now): string
    {
        $over = max(0, $this->activeCount() - $this->capacity + 1);
        $seconds = max($this->slotSeconds, $over * $this->slotSeconds);
        return (now($now->getTimezone())->addSeconds($seconds))->toIso8601String();
    }
}
