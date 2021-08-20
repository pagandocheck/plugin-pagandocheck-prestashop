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
class PagandoPaymentConfirmModuleFrontController extends ModuleFrontController
{
    /**
     * @see FrontController::postProcess()
     */
    public function postProcess()
    {
        /**
         * Get param configuration
         */
        $api_user = Configuration::get('PAGANDO_USER');
        $api_pass = Configuration::get('PAGANDO_PASSWORD');

        /**
         * Init API instance
         */
        $pagando = new Pagando($api_user, $api_pass);

        /**
         * Get all information belonging to order
         */
        $orderId = Tools::getValue('orderId');

        $get_data = (Object)[
            'orderId' => $orderId,
        ];

        $jwt_token = $pagando->getToken();

        if($jwt_token->error){
            $this->redirectError($pagando->error_msg);
        }

        $orderInfo = $pagando->getEcommerceOrderData($get_data);

        /**
         * Get current cart object from session
         */
        // Check if cart is valid
        $cart = new Cart((int)$orderInfo->cartId);

        if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 ||
            $cart->id_address_invoice == 0 || !$this->module->active)
            $this->redirectError('Invalid cart');
        $authorized = false;

        /**
         * Verify if this payment module is authorized
         */
        foreach (Module::getPaymentModules() as $module) {
            if ($module['name'] == 'pagandopayment') {
                $authorized = true;
                break;
            }
        }

        if (!$authorized) {
            die($this->l('This payment method is not available.'));
        }

        /** @var CustomerCore $customer */
        // Check if customer exists
        $customer = new Customer($cart->id_customer);
        if (!Validate::isLoadedObject($customer))
            $this->redirectError('Invalid customer');

        /**
         * Check if this is a vlaid customer account
         */
        if (!Validate::isLoadedObject($customer)) {
            Tools::redirect('index.php?controller=order&step=1');
        }

        $total_paid = $orderInfo->total;
        $extra_vars = array('transaction_id' => $orderInfo->transactionId);
 
        // Build the validation token
        $validation_token = md5($api_pass.$orderInfo->total.$orderInfo->transactionId);
        // Check validation token
        if ($orderInfo->paymentToken != $validation_token)
            $this->redirectError('Invalid payment token');

        // Validate order
        $this->module->validateOrder(
            $cart->id,
            Configuration::get('PS_OS_PAYMENT'),
            $total_paid,
            $this->module->displayName,
            NULL,
            $extra_vars,
            (int)$this->context->currency->id,
            false,
            $customer->secure_key
        );

        /**
         * Redirect the customer to the order confirmation page
         */
        Tools::redirect('index.php?controller=order-confirmation&id_cart=' . (int)$cart->id . '&id_module=' . (int)$this->module->id . '&id_order=' . $this->module->currentOrder . '&key=' . $customer->secure_key);
    }

    protected function redirectError($returnMessage)
    {
        // $this->errors[] = $this->module->getLocaleErrorMapping($returnMessage);
        $this->errors[] = $returnMessage;
        $this->redirectWithNotifications($this->context->link->getPageLink('order', true, null, array(
            'step' => '4')));
    }
}
