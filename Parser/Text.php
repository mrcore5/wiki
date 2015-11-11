<?php namespace Mrcore\Wiki\Parser;

use Layout;

Class Text
{
	
	/**
     * Parse this text $data into XHTML
     *
     * @return string of HTML from unparsed data
     */
    public function parse($data)
	{
		if (Layout::modeIs('raw')) {
			return $data;
		} else {
			return "<pre class='plaintext'>".htmlentities($data)."</pre>";
		}
	}


	/**
	 * Test proper class instantiation
	 *
	 * @return Hello World success text string
	 */
	public static function test()
	{
		return "Hello World from ".get_class();
	}
}
