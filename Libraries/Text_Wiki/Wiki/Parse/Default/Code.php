<?php

/**
* 
* Parses for text marked as a code example block.
* 
* @category Text
* 
* @package Text_Wiki
* 
* @author Paul M. Jones <pmjones@php.net>
* 
* @license LGPL
* 
* @version $Id: Code.php,v 1.11 2007/06/09 23:11:25 justinpatrin Exp $
* 
*/

/**
* 
* Parses for text marked as a code example block.
* 
* This class implements a Text_Wiki_Parse to find sections marked as code
* examples.  Blocks are marked as the string <code> on a line by itself,
* followed by the inline code example, and terminated with the string
* </code> on a line by itself.  The code example is run through the
* native PHP highlight_string() function to colorize it, then surrounded
* with <pre>...</pre> tags when rendered as XHTML.
*
* @category Text
* 
* @package Text_Wiki
* 
* @author Paul M. Jones <pmjones@php.net>
* 
*/

class Text_Wiki_Parse_Code extends Text_Wiki_Parse {
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
    
    #var $regex = '/^(\<code( .+)? >)n(.+)\n(\<\/code\>)(\s|$)/Umsi'; #commented out original, remove space before: note, this regex does NOT work)
    
    #mReschke added the \n after ? > to forse <code> must be on its own line
    #var $regex = ';^<code(\s[^>]*)? >((?:(?R)|.*?)*)\n</code>(\s|$);msi'; #original (remove space before >)
    #var $regex = ';^<code(\s[^>]*)? >\n((?:(?R)|.*?)*)\n</code>(\s|$);msi'; #mreschke's (remove space before >)
    
    #Wow, took this from http://pear.php.net/bugs/bug.php?id=11649&edit=12&patch=code.patch&revision=latest
    #Bug here: http://pear.php.net/bugs/bug.php?id=11649
    #Awesome!! This is what I have been looking for for a long time
    #Its not the php.ini pcre.backtrack_limit or recursion limit that are low
    #Its the regex. Finally I can have HUGE code snippets
    #And I noticed this fix also has the \n so <code>\n must be on its own line
    #Perfect!!
    #mReschke 2010-10-28
    #var $regex = ';\n<code(\s[^>]*)?xxxxxxxxxxx>\n?((?:[^<]*(?R)?.*?)*?)</code>;msi'; #BEAUTIFUL!!
    var $regex = ';<code(\s[^>]*)?>\n?((?:[^<]*(?R)?.*?)*?)</code>;msi'; #BEAUTIFUL!!

    
    /**
    * 
    * Generates a token entry for the matched text.  Token options are:
    * 
    * 'text' => The full matched text, not including the <code></code> tags.
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
        #exit("XX");
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
        
        #return $this->wiki->addToken($this->rule, $options) . @$matches[3];
        return $this->wiki->addToken($this->rule, $options);
    }
}
?>
