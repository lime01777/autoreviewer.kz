<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Dealership;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_view_home_page()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function guest_can_view_catalog()
    {
        $response = $this->get(route('dealerships.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function guest_can_view_dealership_page()
    {
        $dealer = Dealership::factory()->create(['status' => 'published']);
        $response = $this->get(route('dealerships.show', $dealer));
        $response->assertStatus(200);
    }

    /** @test */
    public function guest_can_submit_review()
    {
        $dealer = Dealership::factory()->create(['status' => 'published']);
        
        $response = $this->post(route('reviews.store', $dealer), [
            'author_name' => 'Test User',
            'rating' => 5,
            'text' => 'This is a test review for the project.',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('reviews', [
            'author_name' => 'Test User',
            'status' => 'pending',
            'rating' => 5
        ]);
    }

    /** @test */
    public function pending_review_is_not_displayed()
    {
        $dealer = Dealership::factory()->create(['status' => 'published']);
        $review = Review::factory()->create([
            'dealership_id' => $dealer->id,
            'status' => 'pending',
            'text' => 'Hidden Review'
        ]);

        $response = $this->get(route('dealerships.show', $dealer));
        $response->assertDontSee('Hidden Review');
    }

    /** @test */
    public function approved_review_is_displayed()
    {
        $dealer = Dealership::factory()->create(['status' => 'published']);
        $review = Review::factory()->create([
            'dealership_id' => $dealer->id,
            'status' => 'approved',
            'text' => 'Visible Review'
        ]);

        $response = $this->get(route('dealerships.show', $dealer));
        $response->assertSee('Visible Review');
    }

    /** @test */
    public function rating_calculation_only_uses_approved_reviews()
    {
        $dealer = Dealership::factory()->create();
        
        Review::factory()->create(['dealership_id' => $dealer->id, 'rating' => 5, 'status' => 'approved']);
        Review::factory()->create(['dealership_id' => $dealer->id, 'rating' => 1, 'status' => 'pending']);

        // Since we have an observer, the rating_avg should be updated automatically
        $dealer->refresh();
        
        $this->assertEquals(5.0, $dealer->rating_avg);
    }

    /** @test */
    public function regular_user_cannot_access_admin()
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get('/admin');
        
        // Filament usually redirects or gives 403 depending on implementation
        // Our canAccessPanel returns false for 'user', so it should be forbidden or redirected
        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_access_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/admin');
        
        $response->assertStatus(200);
    }

    /** @test */
    public function sitemap_is_accessible()
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
    }

    /** @test */
    public function robots_is_accessible()
    {
        $response = $this->get('/robots.txt');
        $response->assertStatus(200);
    }
}
