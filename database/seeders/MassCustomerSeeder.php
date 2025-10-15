<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MassCustomerSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating 450 customers...');

        $progressBar = $this->command->getOutput()->createProgressBar(450);
        $progressBar->start();

        for ($i = 1; $i <= 450; $i++) {
            // Generate fake data
            $firstName = fake()->firstName();
            $lastName = fake()->lastName();
            $fullName = $firstName . ' ' . $lastName;
            $email = strtolower($firstName . '.' . $lastName . $i . '@example.com');
            $username = strtolower($firstName . $lastName . $i);

            // Create user account
            $user = User::create([
                'name' => $fullName,
                'email' => $email,
                'username' => $username,
                'password' => Hash::make('password'), // Default password
                'user_type' => 'customer',
                'status' => 'active',
            ]);

            // Create customer
            Customer::create([
                'user_id' => $user->id,
                'customer_name' => $fullName,
                'email' => $email,
                'phone' => '09' . fake()->numerify('#########'), // 09XXXXXXXXX format
                'address' => fake()->address(),
            ]);

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->newLine();
        $this->command->info('✅ 450 customers created successfully!');
        $this->command->info('Default password for all: password');
    }
}
