<?php
/**
 * Plugin passwordcopy: Hide passwords
 * 
 * @author    Vladimír Mach <vladimir@mach.im>
 */

if(!defined('DOKU_INC')) die();
 
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
require_once(DOKU_PLUGIN . 'syntax.php');


class syntax_plugin_passwordcopy extends DokuWiki_Syntax_Plugin {

    function getType() { return 'formatting'; }
    function getAllowedTypes() { return array('formatting', 'substition', 'disabled'); }
    function getSort() { return 158; }
    function connectTo($mode) { $this->Lexer->addEntryPattern('<pass>(?=.*?</pass>)',$mode,'plugin_passwordcopy'); }
    function postConnect() { $this->Lexer->addExitPattern('</pass>','plugin_passwordcopy'); }
 
    /**
     * Create output
     */
    function render($mode, Doku_Renderer $renderer, $data) {
      if($mode == 'xhtml'){
          $r = rand(0,100000);
          list($state, $match) = $data;
            switch ($state) {
              case DOKU_LEXER_ENTER :      
                $renderer->doc .= "<span class=\"pwdbox\">";
                break;
 
              case DOKU_LEXER_UNMATCHED :  
                $value = $renderer->_xmlEntities($match);
                $obfuscated = str_repeat("*", mb_strlen(html_entity_decode($value)));

				$renderer->doc .= "<code id=\"clipID_$r\" ondblclick=\"javaScript:copyClip('clipID_" . $r . "')\" data-mode=\"obfuscated\" data-password=\"" . $value . "\">". $obfuscated . "</code>"; 
				$renderer->doc .= " <a href=\"#\" title=\"Show password\" onclick=\"javaScript:togglePwd('clipID_" . $r . "');return false;\"><span id=\"clipID_" . $r . "_show\" class=\"pass-icon-eye\"></span></a>";
				$renderer->doc .= " <a href=\"#\" title=\"Copy to clipboard\" onclick=\"javaScript:copyClip('clipID_".$r."');return false;\"><span class=\"pass-icon-docs\"></span></a>";
				break;

              case DOKU_LEXER_EXIT :       
              	$renderer->doc .= "</span>"; 
              	break;
            }
            
            return true;
        }
        
        return false;
    }
}
