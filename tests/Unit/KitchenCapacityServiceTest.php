<?php
 use App\Services\KitchenCapacityService;
 use App\Models\Order;
 
 it('computes capacity and suggestion', function () {
    config(['app.kitchen_capacity' => 1, 'app.suggestion_slot_seconds' => 60]);
    Order::query()->delete();
    // 1 active takes us to full
    Order::factory()->create();
    $svc = app(KitchenCapacityService::class);
    expect($svc->hasCapacity())->toBeFalse();
    $suggested = $svc->suggestNextPickup(now());
    expect($suggested)->toBeString();
 });