<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\DailyRecord;

class DailyRecordApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_daily_record()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/daily-records', [
            'date' => now()->toDateString(),
            'amount' => -1500,
            'category' => 'Food',
            'note' => 'ランチ代',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('daily_records', ['category' => 'Food']);
    }

    public function test_unauthenticated_user_cannot_create_daily_record()
    {
        $response = $this->postJson('/api/daily-records', [
            'date' => now()->toDateString(),
            'amount' => -1500,
            'category' => 'Food',
            'note' => 'ランチ代',
        ]);

        $response->assertStatus(401);
    }
}
