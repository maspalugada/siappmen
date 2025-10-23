<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Unit;
use App\Models\Instrument;
use App\Models\Pouch;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test users with roles
        $unitUser = User::factory()->create([
            'name' => 'Unit User',
            'email' => 'unit@siappmen.test',
            'role' => 'unit',
        ]);

        $cssdUser = User::factory()->create([
            'name' => 'CSSD User',
            'email' => 'cssd@siappmen.test',
            'role' => 'cssd',
        ]);

        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@siappmen.test',
            'role' => 'admin',
        ]);

        // Use unit user as default for other operations
        $user = $unitUser;

        // Create units
        $units = collect();
        $units->push(Unit::create(['name' => 'Unit Bedah', 'location' => 'Lantai 2']));
        $units->push(Unit::create(['name' => 'Unit ICU', 'location' => 'Lantai 3']));
        $units->push(Unit::create(['name' => 'Unit Anak', 'location' => 'Lantai 1']));

        // Create instruments
        $instruments = collect();
        $instruments->push(Instrument::create(['code' => 'INS001', 'name' => 'Scalpel', 'description' => 'Pisau bedah', 'is_serialized' => false]));
        $instruments->push(Instrument::create(['code' => 'INS002', 'name' => 'Forceps', 'description' => 'Pinset', 'is_serialized' => false]));
        $instruments->push(Instrument::create(['code' => 'INS003', 'name' => 'Scissors', 'description' => 'Gunting bedah', 'is_serialized' => false]));
        $instruments->push(Instrument::create(['code' => 'INS004', 'name' => 'Needle Holder', 'description' => 'Penjepit jarum', 'is_serialized' => false]));
        $instruments->push(Instrument::create(['code' => 'INS005', 'name' => 'Retractor', 'description' => 'Retraktor', 'is_serialized' => false]));

        // Create pouches
        foreach ($instruments as $instrument) {
            Pouch::create([
                'pouch_code' => 'POUCH-' . $instrument->code . '-01',
                'instrument_id' => $instrument->id,
                'status' => collect(['clean', 'dirty', 'in_use'])->random(),
            ]);
            Pouch::create([
                'pouch_code' => 'POUCH-' . $instrument->code . '-02',
                'instrument_id' => $instrument->id,
                'status' => collect(['clean', 'dirty', 'in_use'])->random(),
            ]);
        }

        // Create orders
        $orders = collect();
        $orders->push(Order::create([
            'order_no' => 'ORD-001',
            'unit_id' => $units->random()->id,
            'requested_by' => $user->id,
            'date_request' => Carbon::now()->subDays(2),
            'date_return_planned' => Carbon::now()->addDays(5),
            'status' => 'pending',
        ]));
        $orders->push(Order::create([
            'order_no' => 'ORD-002',
            'unit_id' => $units->random()->id,
            'requested_by' => $user->id,
            'date_request' => Carbon::now()->subDays(1),
            'date_return_planned' => Carbon::now()->addDays(3),
            'status' => 'completed',
        ]));
        $orders->push(Order::create([
            'order_no' => 'ORD-003',
            'unit_id' => $units->random()->id,
            'requested_by' => $user->id,
            'date_request' => Carbon::now(),
            'date_return_planned' => Carbon::now()->addDays(7),
            'status' => 'pending',
        ]));

        // Create order items
        foreach ($orders as $order) {
            OrderItem::create([
                'order_id' => $order->id,
                'instrument_id' => $instruments->random()->id,
                'qty' => rand(1, 3),
                'notes' => 'Sample order item',
            ]);
        }

        // Create transactions
        for ($i = 1; $i <= 12; $i++) {
            $month = Carbon::now()->month($i)->startOfMonth();
            $transactionCount = rand(5, 15);

            for ($j = 0; $j < $transactionCount; $j++) {
                $transaction = Transaction::create([
                    'type' => collect(['return_dirty', 'pickup', 'borrow', 'distribute', 'handover'])->random(),
                    'reference_id' => $orders->random()->id,
                    'user_id' => $user->id,
                    'unit_id' => $units->random()->id,
                    'occurred_at' => $month->copy()->addDays(rand(0, 27)),
                    'notes' => 'Sample transaction',
                ]);

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'instrument_id' => $instruments->random()->id,
                    'qty' => rand(1, 2),
                    'is_complete' => true,
                    'damaged_qty' => 0,
                ]);
            }
        }
    }
}
