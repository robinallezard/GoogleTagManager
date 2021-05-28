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

namespace GoogleTagManager\Controller;


use GoogleTagManager\GoogleTagManager;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;

/**
 * Class Configuration
 * @package GoogleTagManager\Controller
 * @author Tom Pradat <tpradat@openstudio.fr>
 */
class Configuration extends BaseAdminController
{
    public function saveAction(){

        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), array('googletagmanager'), AccessManager::UPDATE)) {
            return $response;
        }

        $form = new \GoogleTagManager\Form\Configuration($this->getRequest());
        $response=null;

        try {
            $vform = $this->validateForm($form);
            $data = $vform->getData();
            $lang = $this->getRequest()->getSession()->get('thelia.admin.edition.lang');

            GoogleTagManager::setConfigValue('googletagmanager_gtmId', $data['gtmId'], $lang->getlocale());
        } catch (\Exception $e) {
            $this->setupFormErrorContext(
                $this->getTranslator()->trans("Syntax error"),
                $e->getMessage(),
                $form,
                $e
            );
        }

        return $this->generateSuccessRedirect($form);
    }
}