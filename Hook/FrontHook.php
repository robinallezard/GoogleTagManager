<?php
/*************************************************************************************/
/*      This file is part of the GoogleTagManager package.                           */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace GoogleTagManager\Hook;


use GoogleTagManager\GoogleTagManager;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Model\Base\LangQuery;
use Thelia\Model\ConfigQuery;

/**
 * Class FrontHook
 * @package GoogleTagManager\Hook
 * @author Tom Pradat <tpradat@openstudio.fr>
 */
class FrontHook extends BaseHook
{
    public function onMainHeadTop(HookRenderEvent $event){
        $lang = $this->getLang();
        $value = GoogleTagManager::getConfigValue('googletagmanager_gtmId', null, $lang->getLocale());
        if ("" != $value){
            $event->add(
                "<!-- Google Tag Manager -->".
                "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':".
                "new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],".
                "j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=".
                "'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);".
                "})(window,document,'script','dataLayer','".$value."');</script>".
                "<!-- End Google Tag Manager -->"
            );
        }
    }
    public function onMainBodyTop(HookRenderEvent $event){
        $lang = $this->getLang();
        $value = GoogleTagManager::getConfigValue('googletagmanager_gtmId', null, $lang->getLocale());
        if ("" != $value){
            $event->add("<!-- Google Tag Manager (noscript) -->".
                "<noscript><iframe src='https://www.googletagmanager.com/ns.html?id=".$value."' ".
                "height='0' width='0' style='display:none;visibility:hidden'></iframe></noscript>".
                "<!-- End Google Tag Manager (noscript) -->"
            );
        }
    }

    protected function getLang()
    {
        $lang = $this->getRequest()->getSession()->get("thelia.current.lang");
        if (null === $lang){
            $lang = LangQuery::create()->filterByByDefault(1)->findOne();
        }
        return $lang;
    }
}