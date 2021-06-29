<?php

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = 100;
        $purchaseNumber = random_int(2000, 5000);
        $orderNumber = random_int(2000, 5000);

        // Seed test admin
        for ($i = 0; $i < $product; $i++) {
            Product::create([
                'title' => Str::random(random_int(8, 15)),
            ]);
        }

        for ($i = 0; $i < $purchaseNumber; $i++) {
            $totalQuantity = random_int(500, 2000);
            $convinceBill = random_int(500, 2000);
            $totalPrice = random_int(500, 2000);
            $details = random_int(3, 10);
            $randomDate  = Carbon::now()->subDays(rand(0, 365));

            $purchase = Purchase::create([
                'total_quantity' => $totalQuantity + $convinceBill,
                'convince_bill' => $convinceBill,
                'total_price' => $totalPrice + $convinceBill,
                'created_at' => $randomDate,
            ]);

            for ($j = 0; $j < $details; $j++) {
                PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => random_int(1, $product),
                    'quantity' => (int)$totalQuantity / 3,
                    'unit_price' => $totalPrice / 3,
                    'created_at' => $randomDate,
                ]);
            }
        }

        for ($i = 0; $i < $orderNumber; $i++) {
            $totalQuantity = random_int(500, 2000);
            $convinceBill = random_int(500, 2000);
            $totalPrice = random_int(500, 2000);
            $details = random_int(3, 10);
            $randomDate  = Carbon::now()->subDays(rand(0, 365));

            $order = Order::create([
                'company_name' => Str::random(random_int(5, 8)) . ' '.Str::random(random_int(5, 8)),
                'supplier_name' => Str::random(random_int(5, 8)) . ' '.Str::random(random_int(5, 8)),
                'total_quantity' => $totalQuantity,
                'convince_bill' => $convinceBill,
                'total_price' => $totalPrice + $convinceBill,
                'created_at' => $randomDate,
            ]);

            for ($j = 0; $j < 3; $j++) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => random_int(1, $product),
                    'product_name' => Str::random(random_int(5, 8)) . ' '.Str::random(random_int(5, 8)),
                    'quantity' => (int)$totalQuantity / 3,
                    'unit_price' => $totalPrice / 3,
                    'created_at' => $randomDate,
                ]);
            }
        }

    }
}
