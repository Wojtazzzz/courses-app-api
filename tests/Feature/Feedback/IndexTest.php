<?php

namespace Tests\Feature\Feedback;

use App\Models\Feedback;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Illuminate\Support\Str;

class IndexTest extends TestCase
{
    public function test_smoke_test(): void
    {
        $response = $this->getJson(route('api.feedback.index'));

        $response->assertSuccessful();
    }

    public function test_success_when_no_feedback(): void
    {
        $response = $this->getJson(route('api.feedback.index'));

        $response->assertSuccessful()
            ->assertJsonCount(0);
    }

    public function test_return_single_feedback(): void
    {
        $feedback = Feedback::factory()->createOne([
            'content' => 'Simple Content'
        ]);

        $response = $this->getJson(route('api.feedback.index'));

        $response->assertSuccessful()
            ->assertJson(fn (AssertableJson $json) =>
                $json->has(1)
                    ->first(fn (AssertableJson $json) => $json->where('id', $feedback->id)
                        ->where('content', $feedback->content)
                        ->where('user_id', $feedback->user_id)
                )
            );
    }

    public function test_truncate_content_when_has_more_than_135_chars(): void
    {
        $feedback = Feedback::factory()->createOne([
            'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus, iusto dolorum veniam, voluptate libero sit eos voluptas sequi fugit nisi atque doloremque, alias ipsam! Totam voluptatem eius provident dolores quod.'
        ]);

        $response = $this->getJson(route('api.feedback.index'));

        $response->assertSuccessful()
            ->assertJson(fn (AssertableJson $json) =>
                $json->has(1)
                    ->first(fn (AssertableJson $json) =>
                        $json->where('content', Str::limit($feedback->content, 135, '...'))
                            ->etc()
                )
            );
    }

    public function test_return_multiple_feedback(): void
    {
        Feedback::factory(7)->create();

        $response = $this->getJson(route('api.feedback.index'));

        $response->assertSuccessful()
            ->assertJsonCount(7);
    }

    public function test_return_max_10_feedback(): void
    {
        Feedback::factory(13)->create();

        $response = $this->getJson(route('api.feedback.index'));

        $response->assertSuccessful()
            ->assertJsonCount(10);
    }
}
