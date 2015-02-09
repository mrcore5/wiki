<?php
// vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
/**
 * Heading rule end renderer for Xhtml
 *
 * PHP versions 4 and 5
 *
 * @category   Text
 * @package    Text_Wiki
 * @author     Paul M. Jones <pmjones@php.net>
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    CVS: $Id: Heading.php,v 1.10 2005/09/18 13:39:39 toggg Exp $
 * @link       http://pear.php.net/package/Text_Wiki
 */

/**
 * This class renders headings in XHTML.
 *
 * @category   Text
 * @package    Text_Wiki
 * @author     Paul M. Jones <pmjones@php.net>
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Text_Wiki
 */

#Heavely Modified for header content to utilize div and collapse... by mReschke 2011-04-12

class Text_Wiki_Render_Xhtml_Heading extends Text_Wiki_Render {

	var $conf = array(
		'css_h1' => null,
		'css_h2' => null,
		'css_h3' => null,
		'css_h4' => null,
		'css_h5' => null,
		'css_h6' => null
	);

	function token($options)
	{
		#mReschke added <div> for to_top anchor link 2010-10-29
		#mReschke modofied for actual header content with <+ headerhere>...</+> and got the collapse/expand to work on 2011-04-12
		
		static $id;                 //like toc0 toc1 toc2...
		static $text;
		$id_int = substr($id, 3);   //just integer, 0 1 2 ...
		$collapsed = false;          //Default collapse state (set to null to never use collapsing headers) (obsolete by <expand> tag)
		$collapse_on_text = false;   //Collapse/expand when click on header text
		$collapse_link = true;      //Show collapse/expand link
		static $jsOutput = false;   //Output js with each header?, I set to false because I added the js function to my master.js
		$ret = "";

		#if ($view->viewmode_raw) $collapse_link = false;
		if (Session::get('mode') == 'raw') $collapse_link = false;
		#if ($_GET['viewmode'] == 'raw') $collapse_link = false;
		
		// get nice variable names (id, type, level, text)
		extract($options);
		
		//By default all header content is collapsed, but if you have <expand> in the header, it will default expand it
		//The <expand> tag is later removed by my post parser.class.php
		#$collapsed = true;
		#if (isset($_GET['expandall'])) {
		#    $collapsed = false;
		#} else {
		#    if (stristr($text, "<expand>")) $collapsed = false;    
		#}
		
		//Later I changed it to all expand by default, and only collapse if the <collapse> tag is found
		if (Input::has('collapseall')) {
			$collapsed = true;
		} elseif (Input::has('expandall')) {
			$collapsed = false;
		} elseif (Input::get('viewmode') == 'raw') {
			$collapsed = false;
		} else {
			if (stristr($text, "<->")) $collapsed = true;
		}
		
		$endheader = false;
		if (stristr($text, "<endheader>")) $endheader = true;
		
		$pagebreak = '';
		if (stristr($text, "<pagebreak>")) $pagebreak = ' style="page-break-before: always;"';
		
		
		if ($collapsed !== null && $collapse_link) {
			#$collapse_link_html = '<span class="header_toggle"><a id="'.$id.'__link" title="Toggle Content" href="javascript:toggle_wiki_header(\''.$id.'\')">['.($collapsed ? '+' : '-').']</a></span>';
			#$collapse_link_html = '<span class="header_toggle" id="'.$id.'__link" onclick="javascript:toggle_wiki_header(\''.$id.'\')">['.($collapsed ? '+' : '-').']</span>';

			$collapse_link_html = '<span class="header_toggle"><a href="javascript:toggle_wiki_header(\''.$id.'\')" id="'.$id.'__link">['.($collapsed ? '+' : '-').']</a></span>';
			$collapse_link_html .= '<span class="header_collapse"><a href="javascript:toggle_wiki_headers(true);">[--]</a></span>';
			$collapse_link_html .= '<span class="header_expand"><a href="javascript:toggle_wiki_headers(false);">[++]</a></span>';
			
		}

		//Thought of a genius method where I can leave the old style + heading without a ending tag
		//And still know what that actual heading content is.
		//Note, in my topic.view.php page I have 6 starting divs, then the parsed text, then 6 ending divs, to offset this nasty workaround!
		if ($level == 1) {
			$div = "<div><div><div><div><div>";
			$divend = "</div></div></div></div></div></div>";
		} elseif ($level == 2) {
			$div = "<div><div><div><div>";
			$divend = "</div></div></div></div></div>";
		} elseif ($level == 3) {
			$div = "<div><div><div>";
			$divend = "</div></div></div></div>";
		} elseif ($level == 4) {
			$div = "<div><div>";
			$divend = "</div></div></div>";
		} elseif ($level == 5) {
			$div = "<div>";
			$divend = "</div></div>";
		} elseif ($level == 6) {
			$div = "";
			$divend = "</div>";
		}
		
		if ($endheader) {
			if ($type == 'start') {
				$ret .= $divend.$div;
				return $ret;
			}        
		} else {

			switch($type) {
				case 'start':
					// Bootstrap color headers
					$color = '';
					if ($level == 2) {
						$color = 'text-success';
					} elseif ($level == 3) {
						$color = 'text-danger';
					} elseif ($level == 4) {
						$color = 'text-info';
					} elseif ($level == 5) {
						$color = 'text-warning';
					}
					$css = $this->formatConf(' class="%s '.$color.'"', "css_h$level");
					$ret = '';
	
					if ($collapse_on_text) {
						$ret .= $divend.'<h'.$level.$css.' id="'.$id.'"'.$pagebreak.'><span style="cursor:pointer;"'.($collapsed !== null ? ' onclick="toggle_wiki_header(\''.$id.'\');"' : '').'>';
					} else {
						$ret .= $divend.'<h'.$level.$css.' id="'.$id.'"'.$pagebreak.'>';
					}
					return $ret;
					
				case 'end':
					$top_link = '<a href="#top"><div class="heading_top"></div></a>';
					$top_link = ''; #I disabled the top link
					if ($collapse_link) {
						$ret = '';
						if ($collapse_on_text) $ret = '</span>';
						
						#return '</h'.$level.'><a href="#top"><div class="heading_top"></div></a>'.($collapsed !== null ? '<a id="'.$id.'__link" href="javascript:toggle_wiki_header(\''.$id.'\')">['.($collapsed ? '+' : '-').']</a>' : '');
						$ret .= $collapse_link_html.'</h'.$level.'>'.$top_link;
					} else {
						$ret .= $collapse_link_html.'</h'.$level.'>'.$top_link;
					}
					return $ret;
				case 'startContent':
					if ($collapsed !== null) {
						if (!$jsOutput) {
							$js = '';
						} else {
							/*$js = '
								<script language="javascript">
								function toggle_wiki_header(id) {
									div = document.getElementById(id+"__content");
									link = document.getElementById(id+"__link");
									if (div.style.display == "none") {
										div.style.display = "";
										link.innerHTML = "[-]";
									} else {
										div.style.display = "none";
										link.innerHTML = "[+]";
									}
								}
								</script>
							';
							*/
							$js = '';
						}
					} else {
						$js = '';
					}
					#return $js.'<div style="'.($collapsed === true ? 'display: none; ' : '').'padding: 0px; margin: 0px; border: none;" id="'.$id.'__content">
					return '<div class="header'.$level.'_content table-bordered" style="'.($collapsed === true ? 'display: none; ' : 'display: block').'" id="'.$id.'__content">'.$div;
				case 'endContent':
					return '</div>'; //not used, I use my parser.class.php to replace </+..> with </div>
			}
		}
	}
}
?>
