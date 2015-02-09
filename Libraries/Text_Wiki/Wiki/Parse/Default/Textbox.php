<?php

/**
* 
* Parses for text marked as a code example block.
* 
* @category Text
* 
* @package Text_Wiki
* 
* @author mReschke <mail@mreschke.com> and Paul M. Jones <pmjones@php.net>
* 
* @license LGPL
* 
* @version $Id: Textbox.php,v 1.0 2010/10/27 10:20:00 mreschke Exp $
* 
*/

/**
* 
* Parses for text marked as a code example block.
* 
* This class implements a Text_Wiki_Parse to find sections marked as Textbox
* examples.  Blocks are marked as the string <textbox> on a line by itself,
* followed by the inline code example, and terminated with the string
* </textbox> on a line by itself.  The code example is surrounded
* with <textarea>...</textarea> tags when rendered as XHTML.
* Options are <textbox height="200" title="/etc/somefile.ini"></textbox>
*
* @category Text
* 
* @package Text_Wiki
* 
* @author mReschke <mail@mreschke.com> and Paul M. Jones <pmjones@php.net>
* 
*/

class Text_Wiki_Parse_Textbox extends Text_Wiki_Parse {
    
    
    /**
    * 
    * The regular expression used to find source text matching this
    * rule.
    * 
    * @access public
    * 
    * @var string
    * 
    */
    
/*    var $regex = '/^(\<textbox( .+)?\>)\n(.+)\n(\<\/textbox\>)(\s|$)/Umsi';*/
    #var $regex = ';^<textbox(\s[^>]*)? >((?:(?R)|.*?)*)\n</textbox>(\s|$);msi';
	var $regex = ';\n<textbox(\s[^>]*)?>\n?((?:[^<]*(?R)?.*?)*?)</textbox>;msi'; #BEAUTIFUL!! 
    /**
    * 
    * Generates a token entry for the matched text.  Token options are:
    * 
    * 'text' => The full matched text, not including the <textbox></textbox> tags.
    * 
    * @access public
    *
    * @param array &$matches The array of matches from parse().
    *
    * @return A delimited token number to be used as a placeholder in
    * the source text.
    *
    */
    
    function process(&$matches)
    {
        // are there additional attribute arguments?
        $args = trim($matches[1]);
        
        if ($args == '') {
            $options = array(
                'text' => $matches[2],
                'attr' => array('type' => '')
            );
        } else {
        	// get the attributes...
        	$attr = $this->getAttrs($args);
        	
        	// ... and make sure we have a 'type'
        	if (! isset($attr['type'])) {
        		$attr['type'] = '';
        	}
        	
        	// retain the options
            $options = array(
                'text' => $matches[2],
                'attr' => $attr
            );
        }
        
        return $this->wiki->addToken($this->rule, $options) . @$matches[3];
    }
}
?>
