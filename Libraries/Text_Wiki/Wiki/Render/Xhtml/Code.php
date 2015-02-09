<?php

// vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
/**
 * Code rule end renderer for Xhtml
 *
 * PHP versions 4 and 5
 *
 * @category   Text
 * @package    Text_Wiki
 * @author     Paul M. Jones <pmjones@php.net>
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    Code.php,v 1.13 2010/10/27 mReschke Exp $
 * @link       http://pear.php.net/package/Text_Wiki
 */

/**
 * This class renders code blocks in XHTML.
 *
 * @category   Text
 * @package    Text_Wiki
 * @author     Paul M. Jones <pmjones@php.net>
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Text_Wiki
 */
class Text_Wiki_Render_Xhtml_Code extends Text_Wiki_Render {

    var $conf = array(
        'css'      => null, // class for <pre>
        'css_outer' => null, //outer div
        'css_header' => null, //header span
        'css_code' => null, // class for generic <code>
        'css_php'  => null, // class for PHP <code>
        'css_html' => null, // class for HTML <code>
        'css_filename' => null // class for optional filename <div>
    );

    /**
    *
    * Renders a token into text matching the requested format.
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
        $text = $options['text'];
        $attr = $options['attr'];
        $type = strtolower($attr['type']);
        $title = '';
        if (isset($attr['title'])) $title = $attr['title']; //mReschke
        
        $linenum = '';
        if (isset($attr['linenum'])) $linenum = strtolower($attr['linenum']); //mReschke

        $height = '';
        if (isset($attr['height']))	$height = strtolower($attr['height']); //mReschke

        $css = $this->formatConf('class="%s"', 'css');
        $css_outer = $this->formatConf('class="%s"', 'css_outer'); //mReschke
        $css_header = $this->formatConf('class="%s"', 'css_header'); //mReschke
        $css_code = $this->formatConf('class="%s"', 'css_code');
        $css_php = $this->formatConf('class="%s"', 'css_php');
        $css_html = $this->formatConf('class="%s"', 'css_html');
        $css_filename = $this->formatConf('class="%s"', 'css_filename');
        

        if ($height) $height = "style='height:${height}px; overflow:auto;'";
        #if ($title == '') $title = 'Code Snippet';

		//mReschke GeSHi Code Highlighting Addition
        if ($type != '') {
            //Geshi Code Highlighting
            #$title = strtoupper($type).' Code Snippet';
            #eval(Page::load_class('lib/geshi/geshi'));
            $geshi = new \GeSHi($text, $type);
            if ($linenum != '') {
                #$lineNumbers = GESHI_NORMAL_LINE_NUMBERS;
                $lineNumbers = GESHI_FANCY_LINE_NUMBERS;
                #$lineNumbers = GESHI_NO_LINE_NUMBERS;
                $geshi->enable_line_numbers($lineNumbers);
            }
            $text = $geshi->parse_code();
            $text = "<div $css_outer>
                <div $css_header>$title</div>
                <div class='panel-body' $height>$text</div>
            </div>";
        } else {
            //Geshi Plain Text
            $text = $this->textEncode($text);
			$text = "<div $css_outer>
                <div $css_header>$title</div>
                <div class='panel-body'>
                    <pre $css $height><code>$text</code></pre>
                </div>
            </div>";
       }


/* Old, original text_wiki highlighter
        if ($type == 'php') {
            if (substr($options['text'], 0, 5) != '<?php') {
                // PHP code example:
                // add the PHP tags
                $text = "<?php\n" . $options['text'] . "\n?>"; // <?php
            }

            // convert tabs to four spaces
            $text = str_replace("\t", "    ", $text);

            // colorize the code block (also converts HTML entities and adds
            // <code>...</code> tags)
            ob_start();
            highlight_string($text);
            $text = ob_get_contents();
            ob_end_clean();

            // replace <br /> tags with simple newlines.
            // replace non-breaking space with simple spaces.
            // translate HTML <font> and color to XHTML <span> and style.
            // courtesy of research by A. Kalin :-).
            $map = array(
                '<br />'  => "\n",
                '&nbsp;'  => ' ',
                '<font'   => '<span',
                '</font>' => '</span>',
                'color="' => 'style="color:'
            );
            $text = strtr($text, $map);

            // get rid of the last newline inside the code block
            // (becuase higlight_string puts one there)
            if (substr($text, -8) == "\n</code>") {
                $text = substr($text, 0, -8) . "</code>";
            }

            // replace all <code> tags with classed tags
            if ($css_php) {
                $text = str_replace('<code>', "<code$css_php>", $text);
            }

            // done
            #mReschke
            if ($title == '') $title = 'PHP Snippet';
            #$text = "<pre$css>$text</pre>";
            $text = "<div$css_outer><span$css_header>$title</span><pre$css>$text</pre></div>";

        } elseif ($type == 'html' || $type == 'xhtml') {

            // HTML code example:
            // add <html> opening and closing tags,
            // convert tabs to four spaces,
            // convert entities.
            $text = str_replace("\t", "    ", $text);
            $text = "<html>\n$text\n</html>";
            $text = $this->textEncode($text);
            
            #mReschke
            if ($title == '') $title = 'HTML Snippet';
            #$text = "<pre$css><code$css_html>$text</code></pre>";
            $text = "<div$css_outer><span$css_header>$title</span><pre$css><code$css_html>$text</code></pre></div>";

        } else {
            // generic code example:
            // convert tabs to four spaces,
            // convert entities.
            $text = str_replace("\t", "    ", $text);
            $text = $this->textEncode($text);
                        
            #mReschke
            if ($title == '') $title = 'Code Snippet';
            #$text = "<pre$css><code$css_code>$text</code></pre>";
            $text = "<div$css_outer><span$css_header>$title</span><pre$css><code$css_code>$text</code></pre></div>";
        }
*/

        if ($css_filename && isset($attr['filename'])) {
            $text = "<div$css_filename>" .
                $attr['filename'] . '</div>' . $text;
        }

        return "\n$text\n\n";
    }
}
?>
