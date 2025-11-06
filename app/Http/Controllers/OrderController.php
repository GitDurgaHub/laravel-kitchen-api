<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompleteOrderRequest;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use App\Models\Order;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orders) {}
    public function store(CreateOrderRequest $request): JsonResponse
    {
        $result = $this->orders->create($request->validated());
        if (!$result['accepted']) {
            return response()->json([
                'message' => 'Kitchen is at capacity',
                'suggested_next_pickup_time' => $result['suggested_time'],
            ], 429);
        }
        return response()->json([
            'data' => new OrderResource($result['order'])
        ], 201);
    }

    public function active(): JsonResponse
    {
        return response()->json([
            'data' => OrderResource::collection($this->orders->listActive())
        ]);
    }

    public function complete(CompleteOrderRequest $request, Order $order)
    {
        if ($order->status === 'completed') {
            return response()->json([
                'data' => [
                    'id' => $order->id,
                    'status' => 'completed'
                ]
            ]);
        }

        $order->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return response()->json([
            'data' => [
                'id' => $order->id,
                'status' => 'completed'
            ]
        ]);
    }
}
