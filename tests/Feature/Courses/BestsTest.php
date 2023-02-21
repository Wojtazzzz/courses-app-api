<?php

declare(strict_types=1);

namespace Tests\Feature\Courses;

use App\Models\Course;
use App\Models\Rating;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class BestsTest extends TestCase
{
    public function test_smoke_test(): void
    {
        $response = $this->getJson(route('api.courses.bests'));

        $response->assertStatus(200)
            ->assertJson([]);
    }

    public function test_return_max_3_courses(): void
    {
        Course::factory(5)->create();

        $response = $this->getJson(route('api.courses.bests'));

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_return_only_bests_courses_in_correct_order(): void
    {
        Course::factory()
            ->hasRatings(1, ['value' => 2])
            ->createOne(['id' => 1, 'name' => 'React course']);
        Course::factory()
            ->hasRatings(1, ['value' => 4])
            ->createOne(['id' => 2, 'name' => 'Vue course']);
        Course::factory()
            ->hasRatings(1, ['value' => 3])
            ->createOne(['id' => 3, 'name' => 'Symfony course']);
        Course::factory()
            ->hasRatings(1, ['value' => 5])
            ->createOne(['id' => 4, 'name' => 'Laravel course']);

        $response = $this->getJson(route('api.courses.bests'));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has(3)
                    ->has('0', function (AssertableJson $json) {
                        $json->where('id', 4)
                            ->where('name', 'Laravel course')
                            ->where('rating', 5)
                            ->etc();
                    })
                    ->has('1', function (AssertableJson $json) {
                        $json->where('id', 2)
                            ->where('name', 'Vue course')
                            ->where('rating', 4)
                            ->etc();
                    })
                    ->has('2', function (AssertableJson $json) {
                        $json->where('id', 3)
                            ->where('name', 'Symfony course')
                            ->where('rating', 3)
                            ->etc();
                    });
            })
            ->assertJsonMissing([
                'id' => 1,
                'name' => 'React course',
                'rating' => 2,
            ]);
    }

    public function test_return_correct_rating_average(): void
    {
        Course::factory()
            ->hasRatings(10)
            ->createOne();

        $this->assertDatabaseCount('ratings', 10);

        $response = $this->getJson(route('api.courses.bests'));

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json->has(1)->first(function (AssertableJson $json) {
                    $json->where('rating', Rating::avg('value'))
                        ->etc();
                });
            });
    }
}
