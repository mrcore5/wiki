<?php

use Mrcore\Wiki\Models\Tag;
use Illuminate\Database\Seeder;
use Mrcore\Wiki\Models\PostTag;

class WikiTagSeeder extends Seeder
{
	public function run()
	{
		DB::table('tags')->delete();

		Tag::create(array('name' => 'site'));
		#Tag::create(array('name' => 'post-template'));
		#Tag::create(array('name' => 'fixme', 'image' => 'tag3.png', 'post_id' => '18'));
		#Tag::create(array('name' => 'obsolete', 'image' => 'tag4.png', 'post_id' => '19'));


		DB::table('post_tags')->delete();
		PostTag::create(array('post_id' => 1, 'tag_id' => 1));
		PostTag::create(array('post_id' => 2, 'tag_id' => 1));
		PostTag::create(array('post_id' => 3, 'tag_id' => 1));
		PostTag::create(array('post_id' => 4, 'tag_id' => 1));
		PostTag::create(array('post_id' => 5, 'tag_id' => 1));
		PostTag::create(array('post_id' => 6, 'tag_id' => 1));
		PostTag::create(array('post_id' => 7, 'tag_id' => 1));
		PostTag::create(array('post_id' => 8, 'tag_id' => 1));
		PostTag::create(array('post_id' => 9, 'tag_id' => 1));
		PostTag::create(array('post_id' => 10, 'tag_id' => 1));
	}
}
