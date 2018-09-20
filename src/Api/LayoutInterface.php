<?php namespace Mrcore\Wiki\Api;

interface LayoutInterface
{
    public function title($value = null);
    
    public function css($value = null, $prepend = false);
    
    public function removeCss($value);
    
    public function printCss($value = null, $prepend = false);
    
    public function js($value = null, $prepend = false);
    
    public function removeJs($value);
    
    public function script($value = null);
    
    public function mode($value = null);
    
    public function modeIs($value);
    
    public function hideAll($value = false);

    public function hideHeaderbar($value = false);

    public function hideFooterbar($value = false);
    
    public function hideTitlebar($value = false);
    
    public function hideMenubar($value = null);

    public function viewport($value = null);

    public function container($value = null);
}
