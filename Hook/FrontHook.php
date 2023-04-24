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
use GoogleTagManager\Service\GoogleTagService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Model\LangQuery;

/**
 * Class FrontHook
 * @package GoogleTagManager\Hook
 * @author Tom Pradat <tpradat@openstudio.fr>
 */
class FrontHook extends BaseHook
{
    public function __construct(private GoogleTagService $googleTagService, EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct(null, null, $eventDispatcher);
    }

    public function onMainHeadTop(HookRenderEvent $event){
        $lang = $this->getLang();
        $gtmId = GoogleTagManager::getConfigValue('googletagmanager_gtmId', null, $lang->getLocale());
        if ("" != $gtmId){
            $view = $this->request->get('_view');

            $event->add($this->render('datalayer/thelia-page-view.html', ['data' => $this->googleTagService->getTheliaPageViewParameters()]));

            if (in_array($view, ['category', 'brand', 'search'])) {
                $event->add($this->render('datalayer/view-item-list.html', ['eventName' => 'view_item_list']));
            }

            if ($view === 'product') {
                $event->add($this->render('datalayer/view-item.html', ['eventName' => 'view_item']));
            }

            if (null !== $authAction = $this->request->getSession()->get(GoogleTagManager::GOOGLE_TAG_TRIGGER_LOGIN)){
                $event->add($this->render('datalayer/thelia-page-view.html', [
                    'data' => $this->googleTagService->getLogInData($authAction)
                ]));
                $this->request->getSession()->set(GoogleTagManager::GOOGLE_TAG_TRIGGER_LOGIN, null);
            }

            if ($view === 'order-placed') {
                $event->add($this->render('datalayer/thelia-page-view.html', [
                    'data' => $this->googleTagService->getPurchaseData($this->request->get('order_id'))
                ]));
            }

            $event->add(
                "<!-- Google Tag Manager -->".
                "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':".
                "new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],".
                "j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=".
                "'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);".
                "})(window,document,'script','dataLayer','".$gtmId."');</script>".
                "<!-- End Google Tag Manager -->"
            );
        }
    }

    public function onMainBodyTop(HookRenderEvent $event)
    {
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

    public function onMainJsInit(HookRenderEvent $event)
    {
        $view = $this->request->get('_view');

        if (in_array($view, ['category', 'brand', 'search'])) {
            $event->add($this->render('datalayer/select-item.html'));
        }
        $event->add($this->render('datalayer/add-to-cart.html'));
    }

    public function onProductBottom(HookRenderEvent $event)
    {
        $productId = $event->getArgument('product');
        $this->request->getSession()->set(GoogleTagManager::GOOGLE_TAG_VIEW_ITEM, $productId);
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