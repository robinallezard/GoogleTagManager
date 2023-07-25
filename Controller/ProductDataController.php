<?php

namespace GoogleTagManager\Controller;

use GoogleTagManager\Service\GoogleTagService;
use Symfony\Component\HttpFoundation\Request;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Model\Base\CurrencyQuery;
use Thelia\Model\Base\RewritingUrlQuery;
use Thelia\Model\Currency;
use Thelia\Model\Lang;
use Thelia\Model\ProductQuery;
use Thelia\Model\ProductSaleElements;
use Thelia\Model\ProductSaleElementsQuery;

class ProductDataController extends BaseFrontController
{
    public function getProductDataWithUrl(Request $request, GoogleTagService $googleTagService)
    {
        $requestContent = json_decode($request->getContent(), true);
        $productUrl = parse_url($requestContent['productUrl']);
        $result = [];

        if (!isset($productUrl['path'])) {
            return new JsonResponse(json_encode($result));
        }

        $rewriteUrl = RewritingUrlQuery::create()
            ->filterByView('product')
            ->filterByUrl(substr($productUrl['path'], 1))
            ->findOne();

        $session = $request->getSession();

        /** @var Lang $lang */
        $lang = $session->get('thelia.current.lang');

        /** @var Currency $currency */
        $currency = $session->get('thelia.current.currency') ?: CurrencyQuery::create()->filterByByDefault(1)->findOne();


        if (null !== $rewriteUrl) {
            $product = ProductQuery::create()->findPk($rewriteUrl->getViewId());
            $result = $googleTagService->getProductItem($product, $lang, $currency);
        }

        return new JsonResponse(json_encode([$result]));
    }

    public function getCartItem(Request $request, GoogleTagService $googleTagService)
    {
        $requestContent = json_decode($request->getContent(), true);
        $result = [];

        if (!isset($requestContent['pseId']) || !isset($requestContent['quantity'])) {
            return new JsonResponse(json_encode($result));
        }

        $pseId = $requestContent['pseId'];
        $quantity = $requestContent['quantity'];

        $pse = ProductSaleElementsQuery::create()->findPk($pseId);
        $product = $pse->getProduct();

        $session = $request->getSession();

        /** @var Lang $lang */
        $lang = $session->get('thelia.current.lang');

        /** @var Currency $currency */
        $currency = $session->get('thelia.current.currency') ?: CurrencyQuery::create()->filterByByDefault(1)->findOne();

        $result = $googleTagService->getProductItem($product, $lang, $currency, $pse, $quantity);

        return new JsonResponse(json_encode([$result]));
    }
}