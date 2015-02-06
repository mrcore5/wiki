<?php namespace Mrcore\Modules\Wiki\Parser;

use Mrcore\Modules\Wiki\Parser\Wiki;

Class PhpW
{
	public $userID;
	public $postID;
	public $postCreator;
	public $isAuthenticated;
	public $isAdmin;
	public $disabledRules;


	/**
     * Parse this php $data into XHTML and run it through text_wiki parser
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

		// Run the evaled php through our wiki parser
		$parser = new Wiki();
		$parser->userID = $this->userID;
		$parser->postID = $this->postID;
		$parser->postCreator = $this->postCreator;
		$parser->isAuthenticated = $this->isAuthenticated;
		$parser->isAdmin = $this->isAdmin;
		$parser->disabledRules = $this->disabledRules;
		return $parser->parse($data);

	}

}
