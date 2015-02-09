<?php
// vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
/**
 * Wikilink rule end renderer for Xhtml
 *
 * PHP versions 4 and 5
 *
 * @category   Text
 * @package    Text_Wiki
 * @author     Paul M. Jones <pmjones@php.net>
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    CVS: $Id: Wikilink.php,v 1.22 2006/12/08 21:25:24 justinpatrin Exp $
 * @link       http://pear.php.net/package/Text_Wiki
 */

/**
 * This class renders wiki links in XHTML.
 *
 * @category   Text
 * @package    Text_Wiki
 * @author     Paul M. Jones <pmjones@php.net>
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Text_Wiki
 */
class Text_Wiki_Render_Xhtml_Wikilink extends Text_Wiki_Render {

    var $conf = array(
        'pages' => array(), // set to null or false to turn off page checks
        'view_url' => 'http://example.com/index.php?page=%s',
        'new_url'  => 'http://example.com/new.php?page=%s',
        'new_text' => '?',
        'new_text_pos' => 'after', // 'before', 'after', or null/false
        'css' => null,
        'css_new' => null,
        'exists_callback' => null // call_user_func() callback
    );


    /**
    *
    * Renders a token into XHTML.
    *
    * @access public
    *
    * @param array $options The "options" portion of the token (second
    * element).
    *
    * @return string The text rendered from the token options.
    *
    */

    function token($options)
    {
        // make nice variable names (page, anchor, text)
        extract($options);
        $topic_title = '';
        $start = '';
        $end = '';

        // is there a "page existence" callback?
        // we need to access it directly instead of through
        // getConf() because we'll need a reference (for
        // object instance method callbacks).
        if (isset($this->conf['exists_callback'])) {
            $callback =& $this->conf['exists_callback'];
        } else {
        	$callback = false;
        }

        if ($callback) {
            // use the callback function
            $exists = call_user_func($callback, $page);
        } else {
            // no callback, go to the naive page array.
            $list = $this->getConf('pages');

            // Remember $list is array with title as the key and id as the value
            // $list['My Page'] = 3
            if (is_numeric($page)) {
                $exists = in_array($page, $list);
            } else {
                $exists = isset($list[$page]);
            }
        }

        $anchor = '#'.$this->urlEncode(substr($anchor, 1));

        // does the page exist?
        if ($exists) {

            // PAGE EXISTS.

            // link to the page view, but we have to build
            // the HREF.  we support both the old form where
            // the page always comes at the end, and the new
            // form that uses %s for sprintf()
            $href = $this->getConf('view_url');
            
            //mReschke get pageID and page name
            $topic_id = 0;
            if (is_numeric($page)) {
                //Page is numeric ((33))
                $topic_id = $page;
                $topic_title = array_search($page, $list);
            } else {
                //Page is topic title ((About))
                #$topic_id = array_search(urldecode($page), $list);
                if (isset($list[$page])) {
                    $topic_id = $list[$page];
                };
                #$topic_title = urldecode($page);
            }
            #echo "<br />Topic_id: $topic_id<br />";
            #echo "<br />Topic: $topic_title<br />";

            if (strpos($href, '%s') === false) {
                // use the old form (page-at-end)
                $href = $href . $this->urlEncode($page) . $anchor;
            } else {
                // use the new form (sprintf format string)
                #mReschke
                #$href = sprintf($href, $this->urlEncode($page)) . $anchor;
                #$href = sprintf($href, $this->urlEncode($topic_id.'/'.$topic_title)) . $anchor;
                if (strlen($anchor) > 1) {
                    #$href = sprintf($href, $topic_id.'/'.urlencode($topic_title)) . $anchor;
                    $href = sprintf($href, $topic_id) . $anchor;
                } else {
                    #$href = sprintf($href, $topic_id.'/'.urlencode($topic_title));
                    $href = sprintf($href, $topic_id);
                }
            }

            // get the CSS class and generate output
            $css = ' class="'.$this->textEncode($this->getConf('css')).'"';

            //mReschke
            $start = '<a'.$css.' href="'.$this->textEncode($href).'">';
            if (strlen($anchor) > 1) {
                $start = '<a'.$css.' href="'.$this->textEncode($href).'" onclick="toggle_wiki_headers(false);">';    
            } else {
                $start = '<a'.$css.' href="'.$this->textEncode($href).'">';
            }
            
            
            $end = '</a>';
        } else {

            // PAGE DOES NOT EXIST.
            if (!is_numeric($page)) {
                // link to a create-page url, but only if new_url is set
                $href = $this->getConf('new_url', null);
    
                // set the proper HREF
                if (! $href || trim($href) == '') {
    
                    // no useful href, return the text as it is
                    //TODO: This is no longer used, need to look closer into this branch
                    $output = $text;
    
                } else {
    
                    // yes, link to the new-page href, but we have to build
                    // it.  we support both the old form where
                    // the page always comes at the end, and the new
                    // form that uses sprintf()
                    if (strpos($href, '%s') === false) {
                        // use the old form
                        $href = $href . $this->urlEncode($page);
                    } else {
                        // use the new form
                        $href = sprintf($href, $this->urlEncode($page));
                    }
                }
    
                // get the appropriate CSS class and new-link text
                $css = ' class="'.$this->textEncode($this->getConf('css_new')).'"';
                $new = $this->getConf('new_text');
    
                // what kind of linking are we doing?
                $pos = $this->getConf('new_text_pos');
                if (! $pos || ! $new) {
                    // no position (or no new_text), use css only on the page name
    
                    $start = '<a'.$css.' href="'.$this->textEncode($href).'">';
                    $end = '</a>';
                } elseif ($pos == 'before') {
                    // use the new_text BEFORE the page name
                    $start = '<a'.$css.' href="'.$this->textEncode($href).'">'.$this->textEncode($new).'</a>';
                    $end = '';
                } else {
                    // default, use the new_text link AFTER the page name
                    $start = '';
                    $end = '<a'.$css.' href="'.$this->textEncode($href).'">'.$this->textEncode($new).'</a>';
                }
            }
        }
        #mReschke if no alternate text ((33|alternate)), then use topic_title
        if (is_numeric($text)) {
            //Means no |alternate)
            $text = $topic_title;
        }
        
        if (!strlen($text)) {
            $start .= $this->textEncode($page);
        }
        if (isset($type)) {
            switch ($type) {
            case 'start':
                $output = $start;
                break;
            case 'end':
                $output = $end;
                break;
            }
        } else {
            #$output = $start.$this->textEncode($text).$end;
            $output = $start.$this->textEncode(urldecode($text)).$end; #mReschke
        }
        return $output;
    }
}
?>
