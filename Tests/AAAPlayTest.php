<?php namespace Mrcore\Wiki;

use TestCase;
use Mockery as m;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

use Mrcore\Wiki\Models\Post;

class AAAPlayTest extends TestCase
{
        public function init()
        {
            #$this->iam = $this->app->make('Dynatron\Iam');
            #$this->vfi = $this->app->make('Dynatron\Vfi');
        }

        protected function play()
        {

			$posts = Post::find(1);
			dd($posts->tags);


        }

        protected function playPackage()
        {
            dd('vfi play package here');
        }

        public function testEmpty() {}
        public function tearDown() { m::close(); }
        public function setUp()
        {
            parent::setUp();
            $this->init();
            foreach ($_SERVER['argv'] as $arg) {
                if (str_contains($arg, 'play')) {
                    $method = "play".studly_case(substr($arg, 5));
                    $this->$method();
                    exit();
                }
            }
        }

}
