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
use Thelia\Model\ConfigQuery;

/**
 * Class FrontHook
 * @package GoogleTagManager\Hook
 * @author Tom Pradat <tpradat@openstudio.fr>
 */
class FrontHook extends BaseHook
{
    public function onMainHeadTop(HookRenderEvent $event){
        $value = GoogleTagManager::getConfigValue('googletagmanager_trackingcode');
        if ("" != $value){
            $event->add($value);
        }
    }
}