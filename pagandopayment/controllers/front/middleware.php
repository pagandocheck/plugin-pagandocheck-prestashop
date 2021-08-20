<?php
/*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

include_once _PS_MODULE_DIR_.'pagandopayment/core/Pagando.php';


/**
 * @since 1.0.0
 */
class PagandoPaymentMiddlewareModuleFrontController extends ModuleFrontController
{
    /**
     * @see FrontController::postProcess()
     */
    public function postProcess()
    {

        $cart = $this->context->cart;
        if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active) {
            Tools::redirect('index.php?controller=order&step=1');
        }

        // Check that this payment option is still available in case the customer changed his address just before the end of the checkout process
        $authorized = false;
        foreach (Module::getPaymentModules() as $module) {
            if ($module['name'] == 'pagandopayment') {
                $authorized = true;
                break;
            }
        }

        if (!$authorized) {
            die($this->module->l('This payment method is not available.', 'validation'));
        }

        $customer = new Customer($cart->id_customer);
        if (!Validate::isLoadedObject($customer)){
            Tools::redirect('index.php?controller=order&step=1');
        }

        /**
         * Get param configuration
         */
        $api_user = Configuration::get('PAGANDO_USER');
        $api_pass = Configuration::get('PAGANDO_PASSWORD');
        $api_concept = Configuration::get('PAYMENT_CONCEPT');

        $order_params = array(
            'cart' => $cart,
            'api_concept' => $api_concept,
        );

        /**
         * Init API instance
         */
        $pagando = new Pagando($api_user, $api_pass, $order_params);

        /**
         * Get jwt token
         */
        $jwt_token = $pagando->getToken();

        if($jwt_token->error){
            $this->redirectError($pagando->error_msg);
        }

        /**
         * Make service call
         */

        $result = $pagando->createEcommerceOrder();

        if ($result->error) {
            $this->redirectError($result->error_msg);
        } else {
            /* Redirect browser */
            Tools::redirect($pagando->getRedirectURIForPagandoCheckout($result, Tools::getValue('mode')));

            /* Make sure that code below does not get executed when we redirect. */
            exit;
        }
    }

    protected function redirectError($returnMessage)
    {
        $this->errors[] = $returnMessage;
        $this->redirectWithNotifications($this->context->link->getPageLink('order', true, null, array(
            'step' => '4')));
    }
}
