<?php

namespace Adithyan\ActivityLogVisualizer\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class ActivityLogVisualizerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_empty_state_if_no_activity()
    {
        $response = $this->get('/activity-log-visualizer');

        $response->assertStatus(200);
        $response->assertSee('No activity logs found');
    }

    /** @test */
    public function it_lists_existing_activities()
    {
        $user = User::factory()->create();

        Activity::create([
            'log_name' => 'default',
            'description' => 'User logged in',
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
            'properties' => ['ip' => '127.0.0.1'],
            'event' => 'login',
        ]);

        $response = $this->get('/activity-log-visualizer');

        $response->assertStatus(200);
        $response->assertSee('User logged in');
        $response->assertSee((string) $user->id);
        $response->assertSee('login');
    }

    /** @test */
    public function it_can_filter_by_event_type()
    {
        $user = User::factory()->create();

        Activity::create([
            'log_name' => 'default',
            'description' => 'User login',
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
            'properties' => ['ip' => '127.0.0.1'],
            'event' => 'login',
        ]);

        Activity::create([
            'log_name' => 'default',
            'description' => 'User logout',
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
            'properties' => ['ip' => '127.0.0.1'],
            'event' => 'logout',
        ]);

        $response = $this->get('/activity-log-visualizer?event=login');

        $response->assertStatus(200);
        $response->assertSee('User login');
        $response->assertDontSee('User logout');
    }

    /** @test */
    public function it_can_filter_by_user()
    {
        $user1 = User::factory()->create(['name' => 'User One']);
        $user2 = User::factory()->create(['name' => 'User Two']);

        Activity::create([
            'log_name' => 'default',
            'description' => 'Activity 1',
            'subject_type' => User::class,
            'subject_id' => $user1->id,
            'causer_type' => User::class,
            'causer_id' => $user1->id,
            'properties' => ['ip' => '127.0.0.1', 'browser' => 'Chrome'],
            'event' => 'update',
        ]);

        Activity::create([
            'log_name' => 'default',
            'description' => 'Activity 2',
            'subject_type' => User::class,
            'subject_id' => $user2->id,
            'causer_type' => User::class,
            'causer_id' => $user2->id,
            'properties' => ['ip' => '127.0.0.1', 'browser' => 'Firefox'],
            'event' => 'update',
        ]);
        $response = $this->get("/activity-log-visualizer?causer_id={$user1->id}");

        $response->assertStatus(200);
        $response->assertSee('Activity 1');

        // Check that User One appears in the activity data
        $response->assertSeeInOrder(['Activity 1', 'User One']);

        // Check that Activity 2 doesn't appear in the table data
        $response->assertDontSee('Activity 2');
    }

    /** @test */
    public function it_can_export_to_csv()
    {
        $user = User::factory()->create();

        Activity::create([
            'log_name' => 'default',
            'description' => 'Test activity',
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
            'properties' => ['ip' => '127.0.0.1', 'browser' => 'Chrome'],
            'event' => 'login',
        ]);

        $response = $this->get('/activity-log-visualizer/export');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        // For streamed content, capture it
        ob_start();
        $response->sendContent();
        $csvContent = ob_get_clean();

        $this->assertStringContainsString('login', $csvContent);
        $this->assertStringContainsString($user->name, $csvContent);
    }

    /** @test */
    public function it_displays_activity_properties_in_modal()
    {
        $user = User::factory()->create();

        Activity::create([
            'log_name' => 'default',
            'description' => 'Test activity',
            'subject_type' => User::class,
            'subject_id' => $user->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
            'properties' => ['ip' => '127.0.0.1', 'browser' => 'Chrome'],
            'event' => 'update',
        ]);

        $response = $this->get('/activity-log-visualizer');

        $response->assertStatus(200);
        $response->assertSee('Test activity');
        $response->assertSee('127.0.0.1');
        $response->assertSee('Chrome');
    }
}
