<?php

use Illuminate\Database\Seeder;
use App\Models\PostComment;

class PostCommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $postComments = factory(PostComment::class)->times(100)->make();
        PostComment::insert($postComments->toArray());
    }
}
