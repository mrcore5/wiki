<?php
// vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:
/**
 * Default: Parses for smileys / emoticons tags
 *
 * This class implements a Text_Wiki_Rule to find source text marked as
 * smileys defined by symbols as ':)' , ':-)' or ':smile:'
 * The symbol is replaced with a token.
 *
 * PHP versions 4 and 5
 *
 * @category   Text
 * @package    Text_Wiki
 * @author     Bertrand Gugger <bertrand@toggg.com>
 * @copyright  2005 bertrand Gugger
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    CVS: $Id: Smiley.php,v 1.6 2005/10/04 08:17:51 toggg Exp $
 * @link       http://pear.php.net/package/Text_Wiki
 */

/**
 * Smiley rule parser class for Default.
 *
 * @category   Text
 * @package    Text_Wiki
 * @author     Bertrand Gugger <bertrand@toggg.com>
 * @copyright  2005 bertrand Gugger
 * @license    http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/Text_Wiki
 * @see        Text_Wiki_Parse::Text_Wiki_Parse()
 */
class Text_Wiki_Parse_Smiley extends Text_Wiki_Parse {

    /**
     * Configuration keys for this rule
     * 'smileys' => array Smileys recognized by this rule, symbols key definitions:
     *              'symbol' => array ( 'name', 'description' [, 'variante', ...] ) as
     *                  ':)'  => array('smile', 'Smile'),
     *                  ':D'  => array('biggrin', 'Very Happy',':grin:'),
     *              the eventual elements after symbol and description are variantes
     *
     * 'auto_nose' => boolean enabling the auto nose feature:
     *                auto build a variante for 2 chars symbols by inserting a '-' as ':)' <=> ':-)'
     *
     * @access public
     * @var array 'config-key' => mixed config-value
     */
    #global $conf;

    // These are the mrcore default but you CAN overwrite them and append to them
    // Just specify a <phpw> tag in your global mrcore topic and global $global_smileys and $global_smileys = array(':)' => array ('smile', 'Smile', '(:'))...
    var $conf = array(
        'smileys' => array(
            ':D'             => array('biggrin', 'Very Happy', ':grin:'),
            ':)'             => array('smile', 'Smile', '(:'),
            ':('             => array('sad', 'Sad', '):'),
            ':o'             => array('surprised', 'Surprised', ':eek:', 'o:'),
            ':shock:'        => array('eek', 'Shocked'),
            ':?'             => array('confused', 'Confused'),
            '8)'             => array('cool', 'Cool', '(8'),
            ':lol:'          => array('lol', 'Laughing'),
            ':x'             => array('mad', 'Mad'),
            ':P'             => array('razz', 'Razz'),
            ':oops:'         => array('redface', 'Embarassed'),
            ':cry:'          => array('cry', 'Crying or Very sad'),
            ':evil:'         => array('evil', 'Evil or Very Mad'),
            ':twisted:'      => array('twisted', 'Twisted Evil'),
            ':roll:'         => array('rolleyes', 'Rolling Eyes'),
            ';)'             => array('wink', 'Wink', '(;'),
            ':|'             => array('neutral', 'Neutral', '|:'),
            ':mrgreen:'      => array('mrgreen', 'Mr. Green'),

            ':new:'          => array('new',' New'),
            ':new2:'         => array('new2',' New'),
            ':new3:'         => array('new3',' New'),
            ':new4:'         => array('new4',' New'),
            ':new5:'         => array('new5',' New'),
            ':hnew5:'        => array('new5_h',' New'),
            ':new6:'         => array('new6',' New'),
            ':hnew6:'        => array('new6_h',' New'),
            ':new7:'         => array('new7',' New'),
            ':hnew7:'        => array('new7_h',' New'),
            ':new8:'         => array('new8',' New'),
            ':hnew8:'        => array('new8_h',' New'),
            ':new9:'         => array('new9',' New'),
            ':new10:'        => array('new10',' New'),
            ':new11:'        => array('new11',' New'),
            ':new12:'        => array('new12',' New'),
            ':hnew12:'       => array('new12_h',' New'),

            ':u:'            => array('up', 'Up'),
            ':d:'            => array('down', 'Down'),
            ':l:'            => array('left', 'Left'),
            ':r:'            => array('right', 'Right'),
            ':u2:'           => array('u2', 'Up'),
            ':d2:'           => array('d2', 'Down'),
            ':l2:'           => array('l2', 'Left'),
            ':r2:'           => array('r2', 'Right'),
            ':u3:'           => array('u3', 'Up'),
            ':d3:'           => array('d3', 'Down'),
            ':l3:'           => array('l3', 'Left'),
            ':r3:'           => array('r3', 'Right'),
            ':u4:'           => array('u4', 'Up'),
            ':d4:'           => array('d4', 'Down'),
            ':l4:'           => array('l4', 'Left'),
            ':r4:'           => array('r4', 'Right'),

            ':!:'            => array('exclaim', 'Exclamation1', ':exclaim:'),
            ':h!:'           => array('exclaim_h', 'Exclamation1', ':hexclaim:'),
            ':!2:'           => array('exclaim2', 'Exclamation2', ':exclaim2:', ':!!:'),
            ':h!2:'          => array('exclaim2_h', 'Exclamation2', ':hexclaim2:', ':h!!:'),
            ':!3:'           => array('exclaim3', 'Exclamation3', ':exclaim3:', ':!!!:'),
            ':h!3:'          => array('exclaim3_h', 'Exclamation3', ':hexclaim3:', ':h!!!:'),
            ':!4:'            => array('exclaim4', 'Exclamation4', ':exclaim4:', ':!!!!:'),
            ':h!4:'          => array('exclaim4_h', 'Exclamation4', ':hexclaim4:', ':h!!!!:'),
            ':!5:'            => array('exclaim5', 'Exclamation4', ':exclaim5:', ':!!!!!:'),
            ':h!5:'          => array('exclaim5_h', 'Exclamation4', ':hexclaim5:', ':h!!!!!:'),
            ':!6:'           => array('exclaim6', 'Exclamation4', ':exclaim6:', ':!!!!!!:'),
            ':h!6:'          => array('exclaim6_h', 'Exclamation4', ':hexclaim6:', ':h!!!!!!:'),

            ':?:'            => array('question', 'Question', ':question:'),
            ':h?:'           => array('question_h', 'Question', ':hquestion:'),
            ':?2:'           => array('question2', 'Question', ':question2:', ':??:'),
            ':h?2:'          => array('question2_h', 'Question', ':hquestion2:', ':h??:'),
            ':?3:'           => array('question3', 'Question', ':question3:', ':???:'),
            ':h?3:'          => array('question3_h', 'Question', ':hquestion3:', ':h???:'),
            ':?4:'           => array('question4', 'Question', ':question4:', ':????:'),
            ':h?4:'          => array('question4_h', 'Question', ':hquestion4:', ':h????:'),
            ':?5:'           => array('question5', 'Question', ':question5:', ':?????:'),
            ':h?5:'          => array('question5_h', 'Question', ':hquestion5:', ':h?????:'),

            ':i:'            => array('idea', 'Idea', ':idea:'),
            ':hi:'           => array('idea_h', 'Idea', ':hidea:'),
            ':i2:'           => array('idea2', 'Idea', ':idea2:', ':ii:'),
            ':hi2:'          => array('idea2_h', 'Idea', ':hidea2:', ':hii:'),
            ':i3:'           => array('idea3', 'Idea', ':idea3:', ':iii:'),
            ':hi3:'          => array('idea3_h', 'Idea', ':hidea3:', ':hiii:'),

		':fixme:'        => array('fixme', 'Fixme'),
            ':hfixme:'       => array('fixme_h', 'Fixme'),
            ':fixme2:'       => array('fixme2', 'Fixme'),
            ':hfixme2:'      => array('fixme2_h', 'Fixme'),
            ':fixme3:'       => array('fixme3', 'Fixme'),
            ':hfixme3:'      => array('fixme3_h', 'Fixme'),

            ':toolbox:'      => array('toolbox', 'Toolbox'),
            ':htoolbox:'     => array('toolbox_h', 'Toolbox'),
            ':tool:'         => array('tool', 'Tool', ':tools:'),
            ':htool:'        => array('tool_h', 'Tool', ':htools:'),
            ':tool2:'        => array('tool2', 'Tool', ':tools2:'),
            ':htool2:'       => array('tool2_h', 'Tool', ':htools2:'),
            ':tool3:'        => array('tool3', 'Tool', ':tools3:'),
            ':htool3:'       => array('tool3_h', 'Tool', ':htools3:'),
            ':tool4:'        => array('tool4', 'Tool', ':tools4:'),
            ':htool4:'       => array('tool4_h', 'Tool', ':htools4:'),

            ':download:'     => array('download', 'Download', ':downloads:'),
            ':hdownload:'    => array('download_h', 'Download', ':hdownloads:'),
            ':download2:'    => array('download2', 'Download', ':downloads2:'),
            ':hdownload2:'   => array('download2_h', 'Download', ':hdownloads2:'),
            ':download3:'    => array('download3', 'Download', ':downloads3:'),
            ':hdownload3:'   => array('download3_h', 'Download', ':hdownloads3:'),
            ':download4:'    => array('download4', 'Download', ':downloads4:'),
            ':hdownload4:'   => array('download4_h', 'Download', ':hdownloads4:'),
            ':download5:'    => array('download5', 'Download', ':downloads5:'),
            ':hdownload5:'   => array('download5_h', 'Download', ':hdownloads5:'),
            ':download6:'    => array('download6', 'Download', ':downloads6:'),
            ':hdownload6:'   => array('download6_h', 'Download', ':hdownloads6:'),
            ':download7:'    => array('download7', 'Download', ':downloads7:'),
            ':hdownload7:'   => array('download7_h', 'Download', ':hdownloads7:'),

            ':upload:'       => array('upload', 'Upload', ':uploads:'),
            ':hupload:'      => array('upload_h', 'Upload', ':huploads:'),
            ':upload2:'      => array('upload2', 'Upload', ':uploads2:'),
            ':hupload2:'     => array('upload2_h', 'Upload', ':huploads2:'),
            ':upload3:'      => array('upload3', 'Upload', ':uploads2:'),
            ':hupload3:'     => array('upload3_h', 'Upload', ':huploads2:'),
            ':upload4:'      => array('upload4', 'Upload', ':uploads2:'),
            ':hupload4:'     => array('upload4_h', 'Upload', ':huploads2:'),
            ':upload5:'      => array('upload5', 'Upload', ':uploads2:'),
            ':hupload5:'     => array('upload5_h', 'Upload', ':huploads2:'),
            ':upload6:'      => array('upload6', 'Upload', ':uploads2:'),
            ':hupload6:'     => array('upload6_h', 'Upload', ':huploads2:'),
            ':upload7:'      => array('upload7', 'Upload', ':uploads2:'),
            ':hupload7:'     => array('upload7_h', 'Upload', ':huploads2:'),

            ':plan:'         => array('plan', 'Plan', ':plans:', ':blueprint:', ':blueprints:'),
            ':hplan:'        => array('plan_h', 'Plan', ':hplans:', ':hblueprint:', ':hblueprints:'),
            ':plan2:'        => array('plan2', 'Plan', ':plans2:', ':blueprint2:', ':blueprints2:'),
            ':hplan2:'       => array('plan2_h', 'Plan', ':hplans2:', ':hblueprint2:', ':hblueprints2:'),            

            ':clipboard:'    => array('clipboard', 'Clipboard'),
            ':hclipboard:'   => array('clipboard_h', 'Clipboard'),
            
            ':comment:'      => array('comment', 'Comment', ':comments:'),
            ':hcomment:'     => array('comment_h', 'Comment', ':hcomments:'),
            ':comment2:'     => array('comment2', 'Comment', ':comments2:'),
            ':hcomment2:'    => array('comment2_h', 'Comment', ':hcomments2:'),
            ':comment3:'     => array('comment3', 'Comment', ':comments3:'),
            ':hcomment3:'    => array('comment3_h', 'Comment', ':hcomments3:'),
            ':comment4:'     => array('comment4', 'Comment', ':comments4:'),
            ':hcomment4:'    => array('comment4_h', 'Comment', ':hcomments4:'),
            ':comment5:'     => array('comment5', 'Comment', ':comments5:'),
            ':hcomment5:'    => array('comment5_h', 'Comment', ':hcomments5:'),
            
            ':construction:'  => array('construction', 'Construction'),
            ':hconstruction:' => array('construction_h', 'Construction'),
            ':construction2:' => array('construction2', 'Construction'),
            ':hconstruction2:'=> array('construction2_h', 'Construction'),
            ':construction3:' => array('construction3', 'Construction'),
            ':hconstruction3:'=> array('construction3_h', 'Construction'),
            
            ':error:'        => array('error', 'Error', ':errors:'),
            ':herror:'       => array('error_h', 'Error', ':herrors:'),
            ':error2:'       => array('error2', 'Error', ':errors2:'),
            ':herror2:'      => array('error2_h', 'Error', ':herrors2:'),
            ':error3:'       => array('error3', 'Error', ':errors3:'),
            ':herror3:'      => array('error3_h', 'Error', ':herrors3:'),
            ':error4:'       => array('error4', 'Error', ':errors4:'),
            ':herror4:'      => array('error4_h', 'Error', ':herrors4:'),
            
            ':graph:'        => array('graph', 'Graph'),
            ':hgraph:'       => array('graph_h', 'Graph'),
            ':graph2:'       => array('graph2', 'Graph'),
            ':hgraph2:'      => array('graph2_h', 'Graph'),
            
            ':help:'         => array('help', 'Help'),
            ':hhelp:'        => array('help_h', 'Help'),
            ':help2:'        => array('help2', 'Help'),
            ':hhelp2:'       => array('help2_h', 'Help'),
            
            ':info:'         => array('info', 'Info', ':summary:'),
            ':hinfo:'        => array('info_h', 'Info', ':hsummary:'),
            ':info2:'        => array('info2', 'Info', ':summary2:'),
            ':hinfo2:'       => array('info2_h', 'Info', ':hsummary2:'),
            
            ':search:'       => array('search', 'Search'),
            ':hsearch:'      => array('search_h', 'Search'),
            
            ':config:'       => array('config', 'Config', ':settings:', ':setting:'),
            ':hconfig:'      => array('config_h', 'Config', ':hsettings:', ':hsetting:'),
            ':config2:'      => array('config2', 'Config', ':settings2:', ':setting2:'),
            ':hconfig2:'     => array('config2_h', 'Config', ':hsettings2:', ':hsetting2:'),
            ':config3:'      => array('config3', 'Config', ':settings3:', ':setting3:'),
            ':hconfig3:'     => array('config3_h', 'Config', ':hsettings3:', ':hsetting3:'),
            ':config4:'      => array('config4', 'Config', ':settings4:', ':setting4:'),
            ':hconfig4:'     => array('config4_h', 'Config', ':hsettings4:', ':hsetting4:'),

            
            ':stop:'         => array('stop', 'Stop'),
            ':hstop:'        => array('stop_h', 'Stop'),
            ':stop2:'        => array('stop2', 'Stop'),
            ':hstop2:'       => array('stop2_h', 'Stop'),
            ':stop3:'        => array('stop3', 'Stop'),
            ':hstop3:'       => array('stop3_h', 'Stop'),
            
            ':add:'          => array('add', 'Add', ':plus:'),
            ':hadd:'         => array('add_h', 'Add', ':hplus:'),
            ':subtract:'     => array('subtract', 'Subtract', ':minus:'),
            ':hsubtract:'    => array('subtract_h', 'Subtract', ':hminus:'),
            ':check:'        => array('check', 'Check'),
            ':hcheck:'       => array('check_h', 'Check'),
            
            ':thumbdown:'    => array('thumbdown', 'Thumb Down', ':thumbsup:'),
            ':thumbup:'      => array('thumbup', 'Thumb Up', ':thumbsdown'),
            ':hthumbdown:'   => array('thumbdown_h', 'Thumb Down', ':hthumbsup:'),
            ':hthumbup:'     => array('thumbup_h', 'Thumb Up', ':hthumbsdown'),
            ':doc:'          => array('doc', 'Document', ':docs:', ':document:', ':documents:'),
            ':hdoc:'         => array('doc_h', 'Document', ':hdocs:', ':hdocument:', ':hdocuments:'),
            
            ':link:'         => array('link', 'Link', ':links:'),
            ':hlink:'        => array('link_h', 'Link', ':hlinks:'),
            ':link2:'        => array('link2', 'Link', ':links2:'),
            ':hlink2:'       => array('link2_h', 'Link', ':hlinks2:'),
            ':link3:'        => array('link3', 'Link', ':links3:'),
            ':hlink3:'       => array('link3_h', 'Link', ':hlinks3:'),
            ':link4:'        => array('link4', 'Link', ':links4:'),
            ':hlink4:'       => array('link4_h', 'Link', ':hlinks4:'),
            ':link5:'        => array('link5', 'Link', ':links5:'),
            ':hlink5:'       => array('link5_h', 'Link', ':hlinks5:'),
            
            ':book:'         => array('book', 'Book', ':books:', ':read:', ':reading:', ':readme:'),
            ':hbook:'        => array('book_h', 'Book', ':hbooks:', ':hread:', ':hreading:', ':hreadme:'),
            ':anchor:'       => array('anchor', 'Anchor', ':anchors:'),
            ':hanchor:'      => array('anchor_h', 'Anchor', ':hanchors:'),

            ':note:'         => array('note', 'Postit', ':postits:', ':sticky:', ':stickies:', ':postit:', ':notes:'),
            ':hnote:'        => array('note_h', 'Postit', ':hpostits:', ':hsticky:', ':hstickies:', ':hpostit:', ':hnotes:'),
            ':note2:'        => array('note2', 'Postit', ':postits2:', ':sticky2:', ':stickies2:', ':postit2:', ':notes2:'),
            ':hnote2:'       => array('note2_h', 'Postit', ':hpostits2:', ':hsticky2:', ':hstickies2:', ':hpostit2:', ':hnotes2:'),
            ':note3:'        => array('note3', 'Postit', ':postits3:', ':sticky3:', ':stickies3:', ':postit3:', ':notes3:'),
            ':hnote3:'       => array('note3_h', 'Postit', ':hpostits3:', ':hsticky3:', ':hstickies3:', ':hpostit3:', ':hnotes3:'),
            
            ':map:'          => array('map', 'Map', ':maps:', ':goal:', ':goals:'),
            ':hmap:'         => array('map_h', 'Map', ':maps:', ':hgoal:', ':hgoals:'),
            ':map2:'         => array('map2', 'Map', ':maps2:', ':goal2:', ':goals2:'),
            ':hmap2:'        => array('map2_h', 'Map', ':maps2:', ':hgoal2:', ':hgoals2:'),
            
            ':split:'        => array('split', 'Split'),
            ':hsplit:'       => array('split_h', 'Split'),
            ':join:'         => array('join', 'Join'),
            ':hjoin:'        => array('join_h', 'Join'),
            ':refresh:'      => array('refresh', 'Refresh'),
            ':hrefresh:'     => array('refresh_h', 'Refresh'),
            ':refresh2:'     => array('refresh2', 'Refresh'),
            ':hrefresh2:'    => array('refresh2_h', 'Refresh'),
            ':refresh3:'     => array('refresh3', 'Refresh'),
            ':hrefresh3:'    => array('refresh3_h', 'Refresh'),
            ':undo:'         => array('undo', 'Undo'),
            ':hundo:'        => array('undo_h', 'Undo'),
            ':redo:'         => array('redo', 'Redo'),
            ':hredo:'        => array('redo_h', 'Redo'),
            
            ':resource:'     => array('resource', 'Resource', ':resources:', ':reference:', ':references:'),
            ':hresource:'    => array('resource_h', 'Resource', ':hresources:', ':hreference:', ':hreferences:'),
            ':resource2:'    => array('resource2', 'Resource', ':resources2:', ':reference2:', ':references2:'),
            ':hresource2:'   => array('resource2_h', 'Resource', ':hresources2:', ':hreference2:', ':hreferences2:'),
            
            ':shell:'        => array('shell', 'Shell'),
            ':hshell:'       => array('shell_h', 'Shell'),
            ':shell2:'       => array('shell2', 'Shell'),
            ':hshell2:'      => array('shell2_h', 'Shell'),
            ':shell3:'       => array('shell3', 'Shell'),
            ':hshell3:'      => array('shell3_h', 'Shell'),
            ':shell4:'       => array('shell4', 'Shell'),
            ':hshell4:'      => array('shell4_h', 'Shell'),
            
            ':flag:'         => array('flag', 'Flag', ':flags:'),
            ':hflag:'        => array('flag_h', 'Flag', ':hflags:'),
            ':flag2:'        => array('flag2', 'Flag', ':flags2:'),
            ':hflag2:'       => array('flag2_h', 'Flag', ':hflags2:'),
            ':flag3:'        => array('flag3', 'Flag', ':flags3:'),
            ':hflag3:'       => array('flag3_h', 'Flag', ':hflags3:'),
            ':flag4:'        => array('flag4', 'Flag', ':flags4:'),
            ':hflag4:'       => array('flag4_h', 'Flag', ':flags4:'),
            ':flag5:'        => array('flag5', 'Flag', ':flags5:'),
            ':hflag5:'       => array('flag5_h', 'Flag', ':flags5:'),
            ':flag6:'        => array('flag6', 'Flag', ':flags6:'),
            ':hflag6:'       => array('flag6_h', 'Flag', ':flags6:'),
            
            ':star:'         => array('star', 'Star'),
            ':hstar:'        => array('star_h', 'Star'),
            ':star2:'        => array('star2', 'Star'),
            ':hstar2:'       => array('star2_h', 'Star'),
            ':star3:'        => array('star3', 'Star'),
            ':hstar3:'       => array('star3_h', 'Star'),
            ':star4:'        => array('star4', 'Star'),
            ':hstar4:'       => array('star4_h', 'Star'),
            ':star5:'        => array('star5', 'Star'),
            ':hstar5:'       => array('star5_h', 'Star'),
            
            ':related:'      => array('related', 'Related'),
            ':hrelated:'     => array('related_h', 'Related'),
            ':related2:'     => array('related2', 'Related'),
            ':hrelated2:'    => array('related2_h', 'Related'),
            
            ':install:'      => array('install', 'Install'),
            ':hinstall:'     => array('install_h', 'Install'),
            ':install2:'     => array('install2', 'Install'),
            ':hinstall2:'    => array('install2_h', 'Install'),
            ':install3:'     => array('install3', 'Install'),
            ':hinstall3:'    => array('install3_h', 'Install'),
            ':install4:'     => array('install4', 'Install'),
            ':hinstall4:'    => array('install4_h', 'Install'),
            ':install5:'     => array('install5', 'Install'),
            ':hinstall5:'    => array('install5_h', 'Install'),

            ':network:'      => array('network', 'Network'),
            ':hnetwork:'     => array('network_h', 'Network'),
            ':network2:'     => array('network2', 'Network'),
            ':hnetwork2:'    => array('network2_h', 'Network'),
            ':network3:'     => array('network3', 'Network'),
            ':hnetwork3:'    => array('network3_h', 'Network'),
            ':network4:'     => array('network4', 'Network'),
            ':hnetwork4:'    => array('network4_h', 'Network'),
            ':network5:'     => array('network5', 'Network'),
            ':hnetwork5:'    => array('network5_h', 'Network'),

            ':code:'         => array('code', 'Code'),
            ':hcode:'        => array('code_h', 'Code'),
            ':code2:'        => array('code2', 'Code'),
            ':hcode2:'       => array('code2_h', 'Code'),
            ':code3:'        => array('code3', 'Code'),
            ':hcode3:'       => array('code3_h', 'Code'),
            ':code4:'        => array('code4', 'Code'),
            ':hcode4:'       => array('code4_h', 'Code'),
            ':code5:'        => array('code5', 'Code'),
            ':hcode5:'       => array('code5_h', 'Code'),
            ':code6:'        => array('code6', 'Code'),
            ':hcode6:'       => array('code6_h', 'Code'),

            
            ':hd:'           => array('hd', 'Hard Drive', ':harddrive:', ':hds:', ':harddrives:'),
            ':hhd:'          => array('hd_h', 'Hard Drive', ':hharddrive:', ':hhds:', ':hharddrives:'),
            ':hd2:'          => array('hd2', 'Hard Drive', ':harddrive2:', ':hds2:', ':harddrives2:'),
            ':hhd2:'         => array('hd2_h', 'Hard Drive', ':hharddrive2:', ':hhds2:', ':hharddrives2:'),            
            ':hd3:'          => array('hd3', 'Hard Drive', ':harddrive3:', ':hds3:', ':harddrives3:'),
            ':hhd3:'         => array('hd3_h', 'Hard Drive', ':hharddrive3:', ':hhds3:', ':hharddrives3:'),            

            ':computer:'     => array('computer', 'Computer'),
            ':hcomputer:'    => array('computer_h', 'Computer'),
            ':computer2:'    => array('computer2', 'Computer'),
            ':hcomputer2:'   => array('computer2_h', 'Computer'),
            ':computer3:'    => array('computer3', 'Computer'),
            ':hcomputer3:'   => array('computer3_h', 'Computer'),
            ':computer4:'    => array('computer4', 'Computer'),
            ':hcomputer4:'   => array('computer4_h', 'Computer'),
            ':computer5:'    => array('computer5', 'Computer'),
            ':hcomputer5:'   => array('computer5_h', 'Computer'),

            ':server:'       => array('server', 'Server'),
            ':hserver:'      => array('server_h', 'Server'),
            ':server2:'      => array('server2', 'Server'),
            ':hserver2:'     => array('server2_h', 'Server'),
            ':server3:'      => array('server3', 'Server'),
            ':hserver3:'     => array('server3_h', 'Server'),

            ':device:'       => array('device', 'Device'),
            ':hdevice:'      => array('device_h', 'Device'),
            ':device2:'      => array('device2', 'Device'),
            ':hdevice2:'     => array('device2_h', 'Device'),
            ':device3:'      => array('device3', 'Device'),
            ':hdevice3:'     => array('device3_h', 'Device'),

            ':os:'           => array('os', 'OS'),
            ':hos:'          => array('os_h', 'OS'),
            ':os2:'          => array('os2', 'OS'),
            ':hos2:'         => array('os2_h', 'OS'),
            ':os3:'          => array('os3', 'OS'),
            ':hos3:'         => array('os3_h', 'OS'),
            ':os4:'          => array('os4', 'OS'),
            ':hos4:'         => array('os4_h', 'OS'),

            ':ram:'          => array('ram', 'RAM'),
            ':hram:'         => array('ram_h', 'RAM'),
            ':ram2:'         => array('ram2', 'RAM'),
            ':hram2:'        => array('ram2_h', 'RAM'),
            ':ram3:'         => array('ram3', 'RAM'),
            ':hram3:'        => array('ram3_h', 'RAM'),

            ':task:'         => array('task', 'Task'),
            ':htask:'        => array('task_h', 'Task'),
            ':task2:'        => array('task2', 'Task'),
            ':htask2:'       => array('task2_h', 'Task'),
            ':task3:'        => array('task3', 'Task'),
            ':htask3:'       => array('task3_h', 'Task'),
            ':task4:'        => array('task4', 'Task'),
            ':htask4:'       => array('task4_h', 'Task'),

            ':calendar:'     => array('calendar', 'Calendar'),
            ':hcalendar:'    => array('calendar_h', 'Calendar'),
            ':calendar2:'    => array('calendar2', 'Calendar'),
            ':hcalendar2:'   => array('calendar2_h', 'Calendar'),
            ':calendar3:'    => array('calendar3', 'Calendar'),
            ':hcalendar3:'   => array('calendar3_h', 'Calendar'),
            ':calendar4:'    => array('calendar4', 'Calendar'),
            ':hcalendar4:'   => array('calendar4_h', 'Calendar'),

            ':user:'         => array('user', 'User'),
            ':huser:'        => array('user_h', 'User'),
            ':user2:'        => array('user2', 'User'),
            ':huser2:'       => array('user2_h', 'User'),
            ':user3:'        => array('user3', 'User'),
            ':huser3:'       => array('user3_h', 'User'),
            ':user4:'        => array('user4', 'User'),
            ':huser4:'       => array('user4_h', 'User'),
            ':user5:'        => array('user5', 'User'),
            ':huser5:'       => array('user5_h', 'User'),

            ':archive:'      => array('archive', 'Archive'),
            ':harchive:'     => array('archive_h', 'Archive'),
            ':archive2:'     => array('archive2', 'Archive'),
            ':harchive2:'    => array('archive2_h', 'Archive'),
            ':archive3:'     => array('archive3', 'Archive'),
            ':harchive3:'    => array('archive3_h', 'Archive'),

            ':lock:'         => array('lock', 'Lock'),
            ':hlock:'        => array('lock_h', 'Lock'),
            ':lock2:'        => array('lock2', 'Lock'),
            ':hlock2:'       => array('lock2_h', 'Lock'),
            ':lock3:'        => array('lock3', 'Lock'),
            ':hlock3:'       => array('lock3_h', 'Lock'),
            ':lock4:'        => array('lock4', 'Lock'),
            ':hlock4:'       => array('lock4_h', 'Lock'),
            ':lock5:'        => array('lock5', 'Lock'),
            ':hlock5:'       => array('lock5_h', 'Lock'),
            ':lock6:'        => array('lock6', 'Lock'),
            ':hlock6:'       => array('lock6_h', 'Lock'),
            ':lock7:'        => array('lock7', 'Lock'),
            ':hlock7:'       => array('lock7_h', 'Lock'),




        ),
        'auto_nose' => true
    );


    /**
     * Definition array of smileys, variantes references their model
     * 'symbol' => array ( 'name', 'description')
     *
     * @access private
     * @var array 'config-key' => mixed config-value
     */
    var $_smileys = array();

     /**
     * Constructor.
     * We override the constructor to build up the regex from config
     *
     * @param object &$obj the base conversion handler
     * @return The parser object
     * @access public
     */
    function Text_Wiki_Parse_Smiley(&$obj)
    {
        $default = $this->conf;
        parent::Text_Wiki_Parse($obj);

        // read the list of smileys to sort out variantes and :xxx: while building the regexp
        $this->_smileys = $this->getConf('smileys', $default['smileys']);
        $autoNose = $this->getConf('auto_nose', $default['auto_nose']);

        // If you define $global_smileys in your global mrcore topic using <phpw> then I will merge those here
        // mReschke 2014-04-30
        global $global_smileys;
        if (isset($global_smileys)) {
            $this->_smileys = array_merge($this->_smileys, $global_smileys);
        }
        
        $reg1 = $reg2 = '';
        $sep1 = ':(?:';
        $sep2 = '';
        foreach ($this->_smileys as $smiley => $def) {
            for ($i = 1; $i < count($def); $i++) {
                if ($i > 1) {
                    $cur = $def[$i];
                    $this->_smileys[$cur] = &$this->_smileys[$smiley];
                } else {
                    $cur = $smiley;
                }
                $len = strlen($cur);
                if (($cur{0} == ':') && ($len > 2) && ($cur{$len - 1} == ':')) {
                    $reg1 .= $sep1 . preg_quote(substr($cur, 1, -1), '#');
                    $sep1 = '|';
                    continue;
                }
                if ($autoNose && ($len === 2)) {
                    $variante = $cur{0} . '-' . $cur{1};
                    $this->_smileys[$variante] = &$this->_smileys[$smiley];
                    $cur = preg_quote($cur{0}, '#') . '-?' . preg_quote($cur{1}, '#');
                } else {
                    $cur = preg_quote($cur, '#');
                }
                $reg2 .= $sep2 . $cur;
                $sep2 = '|';
            }
        }
        #$delim = '[\n\r\s' . $this->wiki->delim . '$^]';
        $delim = '[\n\r\s\>' . $this->wiki->delim . '$^]'; //mreschke added the \> so I can use <box :someicon:>...</box> and it still work with > after last :
        $this->regex = '#(?<=' . $delim .
             ')(' . ($reg1 ? $reg1 . '):' . ($reg2 ? '|' : '') : '') . $reg2 .
             ')(?=' . $delim . ')#i';
    }

    /**
     * Generates a replacement token for the matched text.  Token options are:
     *     'symbol' => the original marker
     *     'name' => the name of the smiley
     *     'desc' => the description of the smiley
     *
     * @param array &$matches The array of matches from parse().
     * @return string Delimited token representing the smiley
     * @access public
     */
    function process(&$matches)
    {
        // tokenize
        return $this->wiki->addToken($this->rule,
            array(
                'symbol' => $matches[1],
                'name'   => $this->_smileys[$matches[1]][0],
                'desc'   => $this->_smileys[$matches[1]][1]
            ));
    }
}

