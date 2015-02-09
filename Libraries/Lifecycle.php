<?php

/**
 * Notice we have no namsepace here
 * This is intentional.  I include this file manually
 * from bootstrap/start.php.  I cannot use a facade but
 * I still want on root namespace for usability
 */
class Lifecycle {

	public static function add($item, $indent = 0)
	{
		#$items = \App::make('mrcore.lifecycle');
		#$items[] = str_repeat("\t", ($indent * 1)).$item;
		#\App::instance('mrcore.lifecycle', $items);	
	}

	public static function get()
	{
		#return \App::make('mrcore.lifecycle');
		return [];
	}

	public static function dump()
	{
		return "Deprecated";
		/*exec('cd '.base_path().'/workbench; pwd -P', $output);
		$workbench = $output[0];

		$items = \App::make('mrcore.lifecycle');
		$out = "<div class='lifecycle'>";

		foreach ($items as $item) {
			$item = preg_replace('"'.$workbench.'/"', "<span class='lifecycle-workbench'>workbench/</span>", $item);
			$item = preg_replace('"'.base_path().'/"', '', $item);
			#$item = preg_replace("'\t'", "&nbsp;&nbsp;&nbsp;&nbsp;", $item);
			$item = preg_replace("'library'", "<span class='lifecycle-library'>library</span>", $item);
			$item = preg_replace("'services/'", "<span class='lifecycle-services'>services/</span>", $item);
			$item = preg_replace("'Services/MrcoreServiceProvider.php'", "Services/<b>MrcoreServiceProvider.php</b>", $item);
			$out .= "<div class='lifecycle-item'>$item</div>";
		}
		$out .= "</div>";

		$out = "
		<style>
			.lifecycle {
				border: 1px solid #ddd;
				font-family: monospace;
				padding: 5px;
				margin: 12px;
			}
			.lifecycle-item {
				margin-bottom: 5px;
			}
			.lifecycle-library {}
			.lifecycle-workbench {
				color: blue;
				font-weight: bold;
			}
		</style>
		".$out;
		return $out;
		*/
	}

	public static function dumpText()
	{
		return "Deprecated";
		/*$items = \App::make('mrcore.lifecycle');

		exec('cd '.base_path().'/workbench; pwd -P', $output);
		$workbench = $output[0];

		$out = '';
		foreach ($items as $item) {
			$item = preg_replace('"'.$workbench.'/"', "workbench/", $item);
			$item = preg_replace('"'.base_path().'/"', '', $item);
			#$item = preg_replace("'\t'", "    ", $item);
			$out .= "$item\n";
		}
		return $out;*/
	}

}