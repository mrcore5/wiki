<?php namespace Mrcore\Wiki\Api;

use Layout as LayoutFacade;

/**
 * This is the layout API layer used from the Mrcore class/facade
 * This layer allows us to change our model columns/properties while
 * maintaining a consistent interface for the wiki users
 */
class Layout implements LayoutInterface
{
    public function title($value = null)
    {
        return LayoutFacade::title($value);
    }

    public function css($value = null, $prepend = false)
    {
        return LayoutFacade::css($value, $prepend);
    }

    public function removeCss($value)
    {
        return LayoutFacade::removeCss($value);
    }

    public function printCss($value = null, $prepend = false)
    {
        return LayoutFacade::printCss($value, $prepend);
    }

    public function js($value = null, $prepend = false)
    {
        return LayoutFacade::js($value, $prepend);
    }

    public function removeJs($value)
    {
        return LayoutFacade::removeJs($value);
    }

    public function script($value = null)
    {
        return LayoutFacade::script($value);
    }

    public function mode($value = null)
    {
        return LayoutFacade::mode($value);
    }

    public function modeIs($value)
    {
        return LayoutFacade::modeIs($value);
    }

    public function hideAll($value = null)
    {
        return LayoutFacade::hideAll($value);
    }

    public function hideHeaderbar($value = null)
    {
        return LayoutFacade::hideHeaderbar($value);
    }

    public function hideFooterbar($value = null)
    {
        return LayoutFacade::hideFooterbar($value);
    }

    public function hideTitlebar($value = null)
    {
        return LayoutFacade::hideTitlebar($value);
    }

    public function hideMenubar($value = null)
    {
        return LayoutFacade::hideMenubar($value);
    }

    public function viewport($value = null)
    {
        return LayoutFacade::viewport($value);
    }

    public function container($value = null)
    {
        return LayoutFacade::container($value);
    }
}
