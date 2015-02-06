<?php namespace Mrcore\Modules\Wiki\Parser;

Class Php
{
	
	/**
     * Parse this php $data into XHTML
     *
     * @return string of HTML from unparsed data
     */
    public function parse($data)
	{

		$data = preg_replace('"^<\?php"i', '', $data);
		$data = preg_replace('"^<\?"i', '', $data);

        ob_start();
        eval($data);
        $data = ob_get_contents();
        ob_end_clean();

		return $data;
	
	}

}
