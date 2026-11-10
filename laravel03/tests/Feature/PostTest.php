<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/40sadasdasd4');

        $response->assertStatus(404);
    }

        public function test_post_index(): void
    {
        $response = $this->get('/post');

        $response->assertStatus(200);

        $response->assertSee('Post Index');
        // $postInDB = Post::all();
        // $response->assertViewHas('posts',function($posts) use ($postInDB) {
        //     return $posts->is($postInDB);
        // });
    }
}
