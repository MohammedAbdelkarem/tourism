<?php
namespace App\Services;

use Carbon\Carbon;
use App\Models\{Order , SubOrder , Medicine};
use App\Http\Resources\Admin\{OrderResource , CustomerResource};
use App\Http\Resources\OrderDetailsResource;
 
class OrderService
{

    private $subOrderService;
    private $medicineService;

    public function __construct(SubOrderService $subOrderService , MedicineService $medicineService)
    {
        $this->subOrderService = $subOrderService;
        $this->medicineService = $medicineService;
    }
    public function createOrder($id)
    {
        Order::create([
            'user_id' => user_id(),
            'admin_id' => $id
        ]);
    }

    public function createSubOrder(array $info)
    {
        $adminId = $info['admin_id'];
        $medicineId = $info['medicine_id'];
        $quantity = $info['required_quantity'];

        $activeOrders = $this->getAdminUserActiveOrders($adminId);

        if($activeOrders->isEmpty())
        {
            $this->createOrder($adminId);
        }

        $orderId = $this->getActiveOrderId($adminId);

        $totalPrice = $this->totalPrice($medicineId , $quantity);

        $this->subOrderService->createSubOrder($quantity , $medicineId , $orderId , $totalPrice);

        $this->updateOrderPrice($orderId , $totalPrice , '+');

        $this->medicineService->updateMedicineQuantity($medicineId , $quantity , '-');

    }

    public function checkMedicine($id)
    {
        // Get the active orders for the admin
        $activeOrders = Order::currentAdminId()->Active()->pluck('id');

        // Check if any of the active orders contain the medicine ID
        $ordersWithMedicine = SubOrder::Orders($activeOrders)->Medicines($id)->exists();

        return $ordersWithMedicine;
    }

    public function getAdminActiveOrders()
    {
        $data = Order::currentAdminId()->active()->get();

        $data = OrderResource::collection($data);

        return $data;
    }
    public function getAdminUserActiveOrders($id)
    {
        $data = Order::adminId($id)->currentUserId()->active()->get();

        return $data;
    }

    public function getActiveOrderId($id)
    {
        $orderId = Order::active()->currentUserId()->adminId($id)->pluck('id')->first();

        return $orderId;
    }

    public function getAdminArchivedOrders(array $date)
    {
        $startDate = isset($date['start'])
        ? Carbon::parse($date['start'])
        : Order::min('created_at');

        $endDate = isset($date['end'])
        ? Carbon::parse($date['end'])
        : Carbon::now();
        
        $data = Order::currentAdminId()->inactive()->dateBetween($startDate , $endDate)->get();

        $data = OrderResource::collection($data);

        return $data;
    }

    public function getCustomers()
    {
        $data = Order::currentAdminId()->active()->get();

        $data = CustomerResource::collection($data);

        return $data;
    }

    public function getCustomerOrders($id)
    {
        $data = Order::userId($id)->currentAdminId()->get();

        $data = OrderResource::collection($data);

        return $data;
    }

    public function getUserOrders()
    {
        $orders = Order::currentUserId()->get();

        $data = OrderResource::collection($orders);

        return  $orders;
    }

    public function getOrderDetails($orderId)
    {
        $order = Order::where('id' , $orderId)->get();

        $order = OrderDetailsResource::collection($order);

        return $order;
    }

    public function totalPrice($id , $quantity)
    {
        $oneItemPrice = Medicine::currentMedicine($id)->pluck('price')->first();

        return $oneItemPrice * $quantity;
    }

    public function updateOrderPrice($orderId , $price , $char)
    {
        $order = Order::find($orderId);

        $order->updatePrice($price , $char);
    }

    public function updateOrder(array $validatedData)
    {
        Order::OrderId($validatedData['id'])->update([
            'order_status' => $validatedData['order_status']
        ]);
    }
    public function updatePayment(array $validatedData)
    {
        Order::OrderId($validatedData['id'])->update([
            'payment_status' => $validatedData['payment_status']
        ]);
    }

    public function deleteEmptyOrder($orderId)
    {
        $price = Order::where('id' , $orderId)->pluck('price')->first();

        if($price == 0)
        {
            Order::find($orderId)->delete();
        }
    }

    public function deleteOrder($orderId)
    {
        $subOrders = $this->subOrderService->getSubOrders($orderId);

        $this->subOrderService->returnBackSubOrders($subOrders);

        Order::find($orderId)->delete();
    }

}