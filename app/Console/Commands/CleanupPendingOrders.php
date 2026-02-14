<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CleanupPendingOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Release stock from abandoned pending orders older than 1 hour';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expirationTime = Carbon::now()->subHour();
        
        $this->info("Checking for orders expiring before: " . $expirationTime->toDateTimeString());

        // Find orders that are 'awaiting_payment' (Online payments initialized but not completed)
        // We do NOT expire 'pending' orders (COD) as they are valid until processed manually.
        $orders = Order::where('payment_status', 'awaiting_payment')
            ->where('created_at', '<', $expirationTime)
            ->with('items.variant')
            ->get();
            
        $count = $orders->count();
        if ($count === 0) {
            $this->info("No expired orders found.");
            return;
        }

        $this->info("Found {$count} expired orders. Processing...");

        foreach ($orders as $order) {
            try {
                // Restore stock
                foreach ($order->items as $item) {
                     if ($item->product_variant_id && $item->variant) {
                         $item->variant->increment('quantity', $item->quantity);
                         $this->line("Restored {$item->quantity} stock for item {$item->id} (Variant: {$item->product_variant_id})");
                     }
                }
                
                $order->status = 'cancelled';
                $order->payment_status = 'expired';
                $order->save();
                
                Log::info("Order {$order->order_number} expired and stock released.");
                $this->info("Expired order {$order->order_number}");
                
            } catch (\Exception $e) {
                Log::error("Failed to expire order {$order->order_number}: " . $e->getMessage());
                $this->error("Failed to process order {$order->order_number}");
            }
        }
        
        $this->info("Cleanup completed.");
    }
}
