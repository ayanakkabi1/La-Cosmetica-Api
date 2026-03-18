<?php

namespace App\Http\Controllers;

use App\DTO\OrderDTO;
use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $user = $request->user();

        return response()->json($this->orderService->getAllForUser($user->id));
    }

    public function store(OrderRequest $request)
    {
        $user = $request->user();
        $dto = new OrderDTO($request->validated());
        $order = $this->orderService->createForUser($user->id, $dto);

        return response()->json($order, 201);
    }

    public function show(Request $request, string $id)
    {
        $user = $request->user();
        $order = $this->orderService->findByIdForUser((int) $id, $user->id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json($order);
    }

    public function destroy(Request $request, string $id)
    {
        $user = $request->user();
        $deleted = $this->orderService->deleteForUser((int) $id, $user->id);

        if (!$deleted) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json(['message' => 'Order deleted']);
    }
}
