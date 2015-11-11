<?php namespace Mrcore\Wiki\Console\Commands;

use Config;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Mrcore\Wiki\Models\Post;
use Mrcore\Wiki\Support\Crypt;
use Mrcore\Wiki\Support\Indexer;
use Mrcore\Wiki\Models\PostIndex;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class IndexPosts extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'mrcore:wiki:index';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Index all un-indexed mrcore wiki posts.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->comment("Indexing all un-indexed posts");

		$id = $this->argument('id');
		if ($id > 0) {
			// Get one post
			self::indexPost(Post::find($id));

		} else {
			// Get all unindexed posts
			$posts = Post::where('deleted', '=', false)->whereRaw('updated_at > indexed_at')->get();
			foreach ($posts as $post) {
				self::indexPost($post);
			}
		}

		#$this->info('Display this on the screen');
		#$this->comment('Display this on the screen');
		#$this->question('Display this on the screen');
		#$this->error('Something went wrong!');
	}

	public function indexPost($post)
	{
		if (isset($post)) {
			$this->info('Indexing Post '.$post->id.' - '.$post->title);
			PostIndex::where('post_id', '=', $post->id)->delete();
			foreach (Indexer::getWords(
				$post->title,
				Crypt::decrypt($post->content),
				$post->badges->lists('name')->all(),
				$post->tags->lists('name')->all()
			) as $word => $weight) {
				// Add word to index
				if (strlen($word) <= 25) {
					$postIndex = new PostIndex;
					$postIndex->post_id = $post->id;
					if (Config::get('mrcore.wiki.use_encryption')) {
						$postIndex->word = md5($word);
					} else {
						$postIndex->word = $word;
					}
					$postIndex->weight = $weight;
					$postIndex->save();

					// Update post indexed_at
					$post->indexed_at = Carbon::now();
					$post->save();
				}
			}
		} else {
			$this->error('Post not found');
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('id', InputArgument::OPTIONAL, 'Post ID'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
		#	array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
