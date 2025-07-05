<?php
// tests/Feature/DailyRecordApiTest.php
use Tests\TestCase;
use App\Models\User;
use App\Models\DailyRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DailyRecordApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_daily_record()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/daily-records', [
            'date' => now()->toDateString(),
            'amount' => 1500,
            'category' => 'Food',
            'note' => 'ランチ代'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('daily_records', ['category' => 'Food']);
    }

    public function test_guest_cannot_create_daily_record()
    {
        $response = $this->postJson('/api/daily-records', [
            'date' => now()->toDateString(),
            'amount' => 1500,
            'category' => 'Food',
            'note' => 'ランチ代'
        ]);

        $response->assertStatus(401); // 認証されていない
    }
}
