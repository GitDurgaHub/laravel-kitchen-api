<?php
namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderService
{
    public function __construct(private readonly KitchenCapacityService $kitchen)
    {}

    public function create(array $data): array
    {
        // VIP bypass
        if (!$data['is_vip'] && !$this->kitchen->hasCapacity()) {
            return [
                'accepted' => false,
                'suggested_time' => $this->kitchen->suggestNextPickup(now()),
            ];
        }

        $order = DB::transaction(fn () => Order::create([
            'items' => $data['items'],
            'pickup_time' => $data['pickup_time'],
            'is_vip' => $data['is_vip'] ?? false,
            'status' => 'active',
        ]));

        return [ 'accepted' => true, 'order' => $order ];
    }

    public function listActive()
    {
        return Order::query()->where('status','active')->orderBy('id','desc')->get();
    }

    public function complete(int $id): Order
    {
        /** @var Order $order */
        $order = Order::query()->findOrFail($id);
        if ($order->status === 'completed') {
             return $order; // idempotent
        }
        $order->update([
        'status' => 'completed',
        'completed_at' => now(),
        ]);
        return $order;
    }
    
    /** Auto-complete orders older than configured seconds */
    public function autoCompleteAged(): int
    {
        $age = (int) config('app.auto_complete_seconds', 900);
        return Order::query()->where('status','active')->where('created_at','<=', now()->subSeconds($age))->update([
        'status' => 'completed',
        'completed_at' => now(),
        ]);
    }
 }