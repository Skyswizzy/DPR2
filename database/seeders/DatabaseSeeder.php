<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // The administrator account. Safe to run twice (matched by username).
        $admin = User::firstOrCreate(
            ['username' => 'ceejay'],
            [
                'name' => 'Inventory Admin',
                'email' => 'admin@pickleball.test',
                'password' => 'password', // hashed by the User model cast
            ]
        );

        // Demonstration data: 8 records, all four categories, both availability statuses.
        $records = [
            [
                'equipment_name' => 'Carbon Fiber Paddle',
                'category' => 'paddles',
                'unit_price' => 4850.00,
                'description' => 'Raw carbon fiber face with a 16 mm polypropylene honeycomb core. 7.9 oz, 5.5 in. grip, edge guard included.',
                'availability_status' => 'available',
            ],
            [
                'equipment_name' => 'Fiberglass Paddle',
                'category' => 'paddles',
                'unit_price' => 2250.00,
                'description' => 'Fiberglass face with a 13 mm core for easy power. 8.1 oz, 5.25 in. grip. Good for beginners.',
                'availability_status' => 'unavailable',
            ],
            [
                'equipment_name' => 'Indoor Pickleball Pack',
                'category' => 'balls',
                'unit_price' => 680.00,
                'description' => 'Pack of 6 indoor balls with 26 large holes. Softer bounce for gym floors. Official size, 26 g.',
                'availability_status' => 'available',
            ],
            [
                'equipment_name' => 'Outdoor Pickleball Pack',
                'category' => 'balls',
                'unit_price' => 750.00,
                'description' => 'Pack of 6 outdoor balls with 40 small holes. Hard, wind-resistant, UV-stable. Official size.',
                'availability_status' => 'available',
            ],
            [
                'equipment_name' => 'Portable Pickleball Net',
                'category' => 'nets',
                'unit_price' => 8900.00,
                'description' => 'Regulation 21 ft 9 in net on an adjustable steel frame. Folds flat, carry bag included.',
                'availability_status' => 'available',
            ],
            [
                'equipment_name' => 'Replacement Net',
                'category' => 'nets',
                'unit_price' => 1800.00,
                'description' => 'Replacement mesh with white top band, 21 ft 9 in wide. Fits standard portable and permanent posts.',
                'availability_status' => 'unavailable',
            ],
            [
                'equipment_name' => 'Paddle Grip Tape',
                'category' => 'accessories',
                'unit_price' => 180.00,
                'description' => 'Pack of 3 tacky polyurethane overgrips, 1.1 mm thick. Fits all standard paddle handles.',
                'availability_status' => 'available',
            ],
            [
                'equipment_name' => 'Pickleball Equipment Bag',
                'category' => 'accessories',
                'unit_price' => 2100.00,
                'description' => 'Sling bag holding 2 paddles and up to 12 balls. Ventilated shoe pocket and zip valuables pouch.',
                'availability_status' => 'unavailable',
            ],
        ];

        foreach ($records as $record) {
            // Creating through the relationship sets user_id = the admin.
            $admin->equipment()->firstOrCreate(
                ['equipment_name' => $record['equipment_name']],
                $record
            );
        }
    }
}
