<?php

use Illuminate\Database\Seeder;
use Mrcore\Wiki\Models\Badge;
use Mrcore\Wiki\Models\PostBadge;

class WikiBadgeSeeder extends Seeder
{
	public function run()
	{
		#DB::table('badges')->delete();

		Badge::create(array(
			'name' => 'SITE',
			'image' => 'badge1.png'
		));

		#DB::table('post_badges')->delete();
		PostBadge::create(array('post_id' => 1, 'badge_id' => 1));
		PostBadge::create(array('post_id' => 2, 'badge_id' => 1));
		PostBadge::create(array('post_id' => 3, 'badge_id' => 1));
		PostBadge::create(array('post_id' => 4, 'badge_id' => 1));
		PostBadge::create(array('post_id' => 5, 'badge_id' => 1));
		PostBadge::create(array('post_id' => 6, 'badge_id' => 1));
		PostBadge::create(array('post_id' => 7, 'badge_id' => 1));
		PostBadge::create(array('post_id' => 8, 'badge_id' => 1));
		PostBadge::create(array('post_id' => 9, 'badge_id' => 1));
		PostBadge::create(array('post_id' => 10, 'badge_id' => 1));
	}
}
