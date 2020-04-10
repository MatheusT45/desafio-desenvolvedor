<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        $product = \App\Models\Product::find($order->product_id);
        $product->available_quantity -= $order->quantity_ordered;
        $product->save();

        $order->total = $order->quantity_ordered * $product->price;
        $order->save();
    }

    /**
     * Handle the order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        if ($order->status === 'CANCELLED') {
            $product = \App\Models\Product::find($order->product_id);
            $product->available_quantity += $order->quantity_ordered;
            $product->save();
        }
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        $product = \App\Models\Product::find($order->product_id);
        $product->available_quantity += $order->quantity_ordered;
        $product->save();
    }

    /**
     * Handle the order "restored" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the order "force deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        $product = \App\Models\Product::find($order->product_id);
        $product->available_quantity += $order->quantity_ordered;
        $product->save();
    }
}
