<?php

use App\Models\PostComment;
use Illuminate\Database\Seeder;

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
