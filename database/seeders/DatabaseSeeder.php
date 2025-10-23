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
        // Create users based on activities/roles in the system

        // Admin users - for system administration and monitoring
        $adminUsers = [
            ['name' => 'Dr. Ahmad Admin', 'email' => 'admin@siappmen.test', 'role' => 'admin'],
            ['name' => 'Sistem Admin', 'email' => 'system@siappmen.test', 'role' => 'admin'],
        ];

        foreach ($adminUsers as $userData) {
            User::factory()->create($userData);
        }

        // CSSD users - for sterilization and instrument management
        $cssdUsers = [
            ['name' => 'Siti CSSD', 'email' => 'cssd1@siappmen.test', 'role' => 'cssd'],
            ['name' => 'Budi CSSD', 'email' => 'cssd2@siappmen.test', 'role' => 'cssd'],
            ['name' => 'Maya Sterilisasi', 'email' => 'cssd3@siappmen.test', 'role' => 'cssd'],
            ['name' => 'Rudi Pengemasan', 'email' => 'cssd4@siappmen.test', 'role' => 'cssd'],
        ];

        $cssdUserObjects = [];
        foreach ($cssdUsers as $userData) {
            $cssdUserObjects[] = User::factory()->create($userData);
        }

        // Unit users - for requesting and using instruments
        $unitUsers = [
            // Unit Bedah
            ['name' => 'Dr. Sari Bedah', 'email' => 'bedah1@siappmen.test', 'role' => 'unit'],
            ['name' => 'Dr. Hendro Bedah', 'email' => 'bedah2@siappmen.test', 'role' => 'unit'],
            ['name' => 'Ns. Lina Bedah', 'email' => 'bedah3@siappmen.test', 'role' => 'unit'],

            // Unit ICU
            ['name' => 'Dr. Rina ICU', 'email' => 'icu1@siappmen.test', 'role' => 'unit'],
            ['name' => 'Dr. Tono ICU', 'email' => 'icu2@siappmen.test', 'role' => 'unit'],
            ['name' => 'Ns. Maya ICU', 'email' => 'icu3@siappmen.test', 'role' => 'unit'],

            // Unit Anak
            ['name' => 'Dr. Ani Anak', 'email' => 'anak1@siappmen.test', 'role' => 'unit'],
            ['name' => 'Dr. Budi Anak', 'email' => 'anak2@siappmen.test', 'role' => 'unit'],
            ['name' => 'Ns. Sari Anak', 'email' => 'anak3@siappmen.test', 'role' => 'unit'],

            // Unit Rawat Inap
            ['name' => 'Dr. Dedi Rawat', 'email' => 'rawat1@siappmen.test', 'role' => 'unit'],
            ['name' => 'Ns. Putri Rawat', 'email' => 'rawat2@siappmen.test', 'role' => 'unit'],

            // Unit IGD
            ['name' => 'Dr. Eka IGD', 'email' => 'igd1@siappmen.test', 'role' => 'unit'],
            ['name' => 'Ns. Rini IGD', 'email' => 'igd2@siappmen.test', 'role' => 'unit'],
        ];

        $unitUserObjects = [];
        foreach ($unitUsers as $userData) {
            $unitUserObjects[] = User::factory()->create($userData);
        }

        // Use first CSSD user as default for other operations
        $user = $cssdUserObjects[0];

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
