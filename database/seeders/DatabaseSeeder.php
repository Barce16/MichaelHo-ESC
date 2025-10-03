<?php

namespace Database\Seeders;

use App\Models\Inclusion;
use App\Models\User;
use App\Models\Package;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $upsertUser = function (array $attrs): User {
            $email = $attrs['email'] ?? null;
            $username = $attrs['username'] ?? null;

            $user = User::query()
                ->when($email, fn($q) => $q->orWhere('email', $email))
                ->when($username, fn($q) => $q->orWhere('username', $username))
                ->first();

            if (empty($attrs['password'])) {
                $attrs['password'] = Hash::make('password');
            }

            if ($user) {
                $user->fill([
                    'name'      => $attrs['name']      ?? $user->name,
                    'username'  => $username           ?? $user->username,
                    'email'     => $email              ?? $user->email,
                    'user_type' => $attrs['user_type'] ?? $user->user_type,
                ]);
                if (array_key_exists('password', $attrs)) {
                    $user->password = $attrs['password'];
                }
                $user->save();
                return $user;
            }

            // Create new
            return User::create($attrs);
        };

        // ---- USERS ----
        $upsertUser([
            'name'      => 'Test User',
            'username'  => 'testuser',
            'email'     => 'test@example.com',
            'user_type' => 'customer',
            'password'  => Hash::make('password'),
        ]);

        $upsertUser([
            'name'      => 'Admin',
            'username'  => 'admin',
            'email'     => 'admin@example.com',
            'user_type' => 'admin',
            'password'  => Hash::make('password'),
        ]);


        // ---- INCLUSIONS ----
        // Insert the inclusions directly as per the SQL provided
        Inclusion::insert([
            [
                'id' => 1,
                'name' => '30 Sets Digital Printing with Free Layout',
                'contact_person' => null,
                'contact_email' => null,
                'contact_phone' => null,
                'price' => 9600.00,
                'category' => 'Invitations',
                'is_active' => 1,
                'notes' => "30 sets Digital Printing\r\n3 pages; 2 regular sized card, 1 small card\r\nFREE LAY-OUT",
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'id' => 2,
                'name' => '30 Pieces with Tags/Labels',
                'contact_person' => null,
                'contact_email' => null,
                'contact_phone' => null,
                'price' => 22000.00,
                'category' => 'Giveaways',
                'is_active' => 1,
                'notes' => "30 pcs.\r\nWith tags/labels\r\nChoices of: Honey Jars, Coffee Bean Jars, Succulents, Tablea Pouch",
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'id' => 3,
                'name' => 'Full Day Coverage with Prenup Shoot',
                'contact_person' => null,
                'contact_email' => null,
                'contact_phone' => null,
                'price' => 46500.00,
                'category' => 'Photos',
                'is_active' => 1,
                'notes' => "Prenuptial/Engagement Shoot\r\nOn-the-Day Coverage\r\nAVP Prenup and SDE\r\n50pcs 5r Prints\r\nUSB Softcopy of Photos\r\n2-4 Photographers",
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'id' => 4,
                'name' => 'Full Day Coverage with Prenup Shoot',
                'contact_person' => null,
                'contact_email' => null,
                'contact_phone' => null,
                'price' => 62000.00,
                'category' => 'Videos',
                'is_active' => 1,
                'notes' => "Prenuptial/Engagement Shoot\r\nHighlights of the Event\r\nAVP Prenup and SDE\r\n2-4 Videographers",
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'id' => 5,
                'name' => '3-Tier Wedding Cake',
                'contact_person' => null,
                'contact_email' => null,
                'contact_phone' => null,
                'price' => 13100.00,
                'category' => 'Cake',
                'is_active' => 1,
                'notes' => "3-tier\r\nDimension:\r\nChoices of Butter and/or Chocolate",
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'id' => 6,
                'name' => 'Prenup and Event Day (10 Heads)',
                'contact_person' => null,
                'contact_email' => null,
                'contact_phone' => null,
                'price' => 15000.00,
                'category' => 'HMUA',
                'is_active' => 1,
                'notes' => "Prenuptial/Engagement Shoot\r\n10 Heads On-the-Day of the Event (including bride)",
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
            [
                'id' => 8,
                'name' => 'Professional Host with Musical Scorer',
                'contact_person' => null,
                'contact_email' => null,
                'contact_phone' => null,
                'price' => 12200.00,
                'category' => 'Host',
                'is_active' => 1,
                'notes' => "With musical scorer",
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ],
        ]);
    }
}
