<?php

use Illuminate\Database\Seeder;
use Mrcore\Wiki\Models\Format;
use Mrcore\Wiki\Models\Type;
use Mrcore\Wiki\Models\Framework;
use Mrcore\Wiki\Models\Mode;

class WikiPostItemsSeeder extends Seeder
{
	public function run()
	{
		// Allow mass assignment
		Eloquent::unguard();

		// Post Formats
		DB::table('formats')->delete();
		Format::create(array('name' => 'Wiki', 'constant' => 'wiki', 'order' => 1));		#1
		Format::create(array('name' => 'PHP', 'constant' => 'php', 'order' => 2));			#2
		Format::create(array('name' => 'PHP (wiki parsed output)', 'constant' => 'phpw', 'order' => 3));		#3
		Format::create(array('name' => 'HTML', 'constant' => 'html', 'order' => 4));		#4
		Format::create(array('name' => 'Text', 'constant' => 'text', 'order' => 6));		#5
		Format::create(array('name' => 'Markdown', 'constant' => 'md', 'order' => 7));		#6
		Format::create(array('name' => 'HTML (wiki parsed output)', 'constant' => 'htmlw', 'order' => 5));		#7

		// Post Types
		DB::table('types')->delete();
		Type::create(array('name' => 'Document', 'constant' => 'doc'));		#1
		Type::create(array('name' => 'Page', 'constant' => 'page'));		#2
		Type::create(array('name' => 'App', 'constant' => 'app'));			#3

		// Post Frameworks
		Framework::create(array('name' => 'Custom', 'constant' => 'custom'));
		Framework::create(array('name' => 'Workbench', 'constant' => 'workbench'));

		// Post Views
		DB::table('modes')->delete();
		Mode::create(array('name' => 'Default', 'constant' => 'default'));	#1
		Mode::create(array('name' => 'Simple', 'constant' => 'simple'));	#2
		Mode::create(array('name' => 'Raw', 'constant' => 'raw'));			#3
		Mode::create(array('name' => 'Source', 'constant' => 'source'));	#4

	}

}
