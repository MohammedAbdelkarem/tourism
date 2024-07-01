<?php
namespace App\Services;

use App\Models\SubOrder;

 
class SubOrderService
{
    private $medicineService;
    private $orderService;

    public function __construct(MedicineService $medicineService , OrderService $orderService)
    {
        $this->medicineService = $medicineService;
        $this->orderService = $orderService;
    }
    public function createSubOrder($quantity , $medicineId , $orderId , $totalPrice)
    {
        SubOrder::create([
            'required_quantity' => $quantity,
            'medicine_id' => $medicineId,
            'order_id' => $orderId,
            'total_price' => $totalPrice,
        ]);
    }

    public function getSubOrders($orderId)
    {
        $subOrders = SubOrder::where('order_id' , $orderId)->get();

        return $subOrders;
    }

    public function returnBackSubOrders($subOrders)
    {
        foreach($subOrders as $sub)
        {
            $medicineId = $sub['medicine_id'];

            $quantity = $sub['required_quantity'];

            $this->medicineService->updateMedicineQuantity($medicineId , $quantity , '+');
        }
    }

    public function deleteSubOrder($subOrderId)
    {
        $subOrder = SubOrder::where('id' , $subOrderId)->get();

        $orderId = $subOrder['order_id'];
        
        $subOrderPrice = $subOrder['total_price'];

        $this->returnBackSubOrders($subOrder);

        SubOrder::find($subOrderId)->delete();

        $this->orderService->updateOrderPrice($orderId , $subOrderPrice , '-');

        $this->orderService->deleteEmptyOrder($orderId);
    }
}