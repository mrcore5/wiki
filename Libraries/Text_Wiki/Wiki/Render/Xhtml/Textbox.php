<?php
// vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
/**
 * Textbox rule end renderer for Xhtml
 *
 * PHP versions 4 and 5
 *
 * @category   Text
 * @package    Text_Wiki
 * @author     mReschke <mail@mreschke.com> and Paul M. Jones <pmjones@php.net>
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    CVS: $Id: Code.php,v 1.13 2006/02/10 23:07:03 toggg Exp $
 * @link       http://pear.php.net/package/Text_Wiki and http://mreschke.com/topic/205
 */

/**
 * This class renders Textbox blocks in XHTML.
 *
 * @category   Text
 * @package    Text_Wiki
 * @author     mReschke <mail@mreschke.com> and Paul M. Jones <pmjones@php.net>
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Text_Wiki and http://mreschke.com/topic/205
 */
class Text_Wiki_Render_Xhtml_Textbox extends Text_Wiki_Render {

    var $conf = array(
        'css'      => null, // class for <pre>
        'css_outer' => null, // class for outer div
        'css_header'  => null, // class for header span
        #'css_html' => null, // class for HTML <code>
        #'css_filename' => null // class for optional filename <div>
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
        @$height = strtolower($attr['height']);
        @$title = strtolower($attr['title']);

        $css      = $this->formatConf(' class="%s"', 'css');
        $css_outer = $this->formatConf(' class="%s"', 'css_outer');
        $css_header  = $this->formatConf(' class="%s"', 'css_header');
        #$css_html = $this->formatConf(' class="%s"', 'css_html');
        #$css_filename = $this->formatConf(' class="%s"', 'css_filename');


        $text = $this->textEncode($text);
        if ($title == '') $title = 'Text Snippet';
        if ($height == '') $height = 200;
        $text = "<div$css_outer><span$css_header>$title</span><textarea$css style='height:${height}px;'disabled='disabled'>$text</textarea></div>";
        #html>\n<div class='textbox_outer'><span class='textbox_header'>$2</span><br /><textarea class='textbox' style='height:$1px;'>\n", $wikiData);        


/*

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
            #$text = "<pre$css>$text</pre>";
            $text = "<div class='pre_outer'><span class='pre_header'>PHP Snippet</span><pre$css>$text</pre></div>";

        } elseif ($type == 'html' || $type == 'xhtml') {

            // HTML code example:
            // add <html> opening and closing tags,
            // convert tabs to four spaces,
            // convert entities.
            $text = str_replace("\t", "    ", $text);
            $text = "<html>\n$text\n</html>";
            $text = $this->textEncode($text);
            
            #mReschke
            #$text = "<pre$css><code$css_html>$text</code></pre>";
            $text = "<div class='pre_outer'><span class='pre_header'>HTML Snippet</span><pre$css><code$css_html>$text</code></pre></div>";

        } else {
            // generic code example:
            // convert tabs to four spaces,
            // convert entities.
            $text = str_replace("\t", "    ", $text);
            $text = $this->textEncode($text);
            
            #mReschke
            #$text = "<pre$css><code$css_code>$text</code></pre>";
            $text = "<div class='pre_outer'><span class='pre_header'>Code Snippet</span><pre$css><code$css_code>$text</code></pre></div>";
        }

        if ($css_filename && isset($attr['filename'])) {
            $text = "<div$css_filename>" .
                $attr['filename'] . '</div>' . $text;
        }
*/
        return "\n$text\n\n";
    }
}
?>
