<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class HashUserPasswords extends Command
{
    protected $signature = 'users:hash-passwords';
    protected $description = 'Hash all user passwords that are not already hashed';

    public function handle()
    {
        $users = User::all();
        $count = 0;

        foreach ($users as $user) {
            // Check if password needs to be hashed
            if (!str_starts_with($user->password, '$2y$')) {
                $user->password = Hash::make($user->password);
                $user->save();
                $count++;
            }
        }

        $this->info("{$count} user passwords have been hashed.");
    }
}
