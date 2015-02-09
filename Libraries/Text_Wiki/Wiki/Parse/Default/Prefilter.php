<?php

/**
* 
* "Pre-filter" the source text.
* 
* @category Text
* 
* @package Text_Wiki
* 
* @author Paul M. Jones <pmjones@php.net>
* 
* @license LGPL
* 
* @version $Id: Prefilter.php,v 1.4 2006/12/08 08:30:37 justinpatrin Exp $
* 
*/

/**
* 
* "Pre-filter" the source text.
* 
* Convert DOS and Mac line endings to Unix, concat lines ending in a
* backslash \ with the next line, convert tabs to 4-spaces, add newlines
* to the top and end of the source text, compress 3 or more newlines to
* 2 newlines.
*
* @category Text
* 
* @package Text_Wiki
* 
* @author Paul M. Jones <pmjones@php.net>
* 
*/

class Text_Wiki_Parse_Prefilter extends Text_Wiki_Parse {
    
    
    /**
    * 
    * Simple parsing method.
    *
    * @access public
    * 
    */
    
    function parse()
    {
        // convert DOS line endings
        $this->wiki->source = str_replace("\r\n", "\n", $this->wiki->source);
        
        // convert Macintosh line endings
        $this->wiki->source = str_replace("\r", "\n", $this->wiki->source);
        
        // concat lines ending in a backslash
        #mReschke 2012-03-05 I disabled this becuase most linux commands I use have multiple lines like this
            # cat some file \
            #   and some other file
        #$this->wiki->source = str_replace("\\\n", "", $this->wiki->source);
        
        // convert tabs to four-spaces
        #mReschke 2012-03-05 turned off, but decided yes I need it, tabs do't copy'n paste into command line
        $this->wiki->source = str_replace("\t", "    ", $this->wiki->source);
        
           
        // add extra newlines at the top and end; this
        // seems to help many rules.
        $this->wiki->source = "\n" . $this->wiki->source . "\n\n";
        $this->wiki->source = str_replace("\n----\n","\n\n----\n\n", $this->wiki->source);
        $this->wiki->source = preg_replace("/\n(\\+{1,6})(.*)\n/m", "\n\n\\1 \\2\n\n", $this->wiki->source);
        
        // finally, compress all instances of 3 or more newlines
        // down to two newlines.
        $find = "/\n{3,}/m";
        $replace = "\n\n";
        $this->wiki->source = preg_replace($find, $replace, $this->wiki->source);
    }

}
?>