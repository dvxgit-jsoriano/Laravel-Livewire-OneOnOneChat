<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Default demo user
        $demoUser = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
                'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=DemoUser',
                'is_online' => true,
            ]
        );

        $wumpus = User::firstOrCreate(
            ['email' => 'wumpus@example.com'],
            [
                'name' => 'Wumpus',
                'password' => Hash::make('password'),
                'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Wumpus',
                'is_online' => true,
            ]
        );

        $loky = User::firstOrCreate(
            ['email' => 'loky@example.com'],
            [
                'name' => 'Loky',
                'password' => Hash::make('password'),
                'avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Loky',
                'is_online' => true,
            ]
        );

        // Direct 1-on-1 conversation: Demo User <-> Wumpus
        $dmWumpus = Conversation::firstOrCreate(
            ['name' => 'Wumpus', 'type' => 'direct'],
            ['avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Wumpus']
        );
        $dmWumpus->users()->syncWithoutDetaching([$demoUser->id, $wumpus->id]);

        // Direct 1-on-1 conversation: Demo User <-> Loky
        $dmLoky = Conversation::firstOrCreate(
            ['name' => 'Loky', 'type' => 'direct'],
            ['avatar' => 'https://api.dicebear.com/7.x/bottts/svg?seed=Loky']
        );
        $dmLoky->users()->syncWithoutDetaching([$demoUser->id, $loky->id]);

        // Seed 1-on-1 messages for Wumpus chat
        if ($dmWumpus->messages()->count() === 0) {
            Message::create([
                'conversation_id' => $dmWumpus->id,
                'user_id' => $demoUser->id,
                'body' => 'ur an emoji',
                'created_at' => now()->subMinutes(10),
            ]);

            Message::create([
                'conversation_id' => $dmWumpus->id,
                'user_id' => $wumpus->id,
                'body' => "😂\nHey! Welcome to our 1-on-1 chat!",
                'created_at' => now()->subMinutes(5),
            ]);
        }

        // Seed 1-on-1 messages for Loky chat
        if ($dmLoky->messages()->count() === 0) {
            Message::create([
                'conversation_id' => $dmLoky->id,
                'user_id' => $loky->id,
                'body' => 'bwhaahahah',
                'created_at' => now()->subMinutes(2),
            ]);
        }
    }
}
