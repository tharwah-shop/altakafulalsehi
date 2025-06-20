<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-test-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test user for testing authentication';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if user already exists
        if (User::where('email', 'user@test.com')->exists()) {
            $this->info('Test user already exists!');
            return;
        }

        // Create test user
        User::create([
            'name' => 'أحمد محمد',
            'email' => 'user@test.com',
            'password' => Hash::make('password123'),
            'phone' => '0501234567',
            'status' => 'active',
        ]);

        $this->info('Test user created successfully!');
        $this->info('Email: user@test.com');
        $this->info('Password: password123');
    }
}
