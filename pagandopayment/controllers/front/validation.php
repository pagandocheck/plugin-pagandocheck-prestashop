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
class PagandoPaymentValidationModuleFrontController extends ModuleFrontController
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

        $this->context->smarty->assign([
            'params' => $_REQUEST,
        ]);

        $this->setTemplate('module:pagandopayment/views/templates/front/payment_return.tpl');

        $customer = new Customer($cart->id_customer);
        if (!Validate::isLoadedObject($customer)){
            Tools::redirect('index.php?controller=order&step=1');
        }

        $api_user = Configuration::get('PAGANDO_USER');
        $api_pass = Configuration::get('PAGANDO_PASSWORD');
        $api_concept = Configuration::get('PAYMENT_CONCEPT');

        $card = array(
            'pan' => $_GET['card_pan'],
            'cvv' => $_GET['card_cvv'],
            'exp_month' => $_GET['card_exp_month'],
            'exp_year' => $_GET['card_exp_year'],
            'card_brand' => $_GET['card_brand'],
            'name' => $_GET['card_name'],
            'street' => $_GET['card_street'],
            'noExt' => $_GET['card_noExt'],
            'noInt' => $_GET['card_noInt'],
            'zipCode' => $_GET['card_zipCode'],
            'city' => $_GET['card_city'],
            'district' => $_GET['card_district'], // Se enviaban con el mismo
            'state' => $_GET['card_state'],
            'country' => $_GET['card_country'],
            'aft_token' => $_GET['aft_token'],
        );

        $promotion = array(
            'promotionType' => $_GET['card_promotion_promotion_type'],
            'timeToApply' => $_GET['card_promotion_promotion_time_to_apply'],
            'monthsToWait' => $_GET['card_promotion_promotion_months_to_wait']
        );

        $order_params = array(
            'cart' => $cart,
            'card' => $card,
            'promotion' => $promotion,
            'api_concept' => $api_concept,
            'aft_token' => $_GET['aft_token'],
        );
        
        $pagando = new Pagando($api_user, $api_pass, $order_params, true);

        $currency = $this->context->currency;
        $total = (float)$cart->getOrderTotal(true, Cart::BOTH);
        $mailVars = array(
            '{transaction_id}' => Configuration::get('BANK_WIRE_OWNER')
        );

        if($pagando->error){
            $this->redirectError($pagando->error_msg);
        } else {
            $this->module->validateOrder($cart->id, Configuration::get('PS_OS_PAYMENT'), $total, $this->module->displayName, NULL, $mailVars, (int)$currency->id, false, $customer->secure_key);
            Tools::redirect('index.php?controller=order-confirmation&id_cart='.$cart->id.'&id_module='.$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key);
        }
    }

    protected function redirectError($returnMessage)
    {
        $this->errors[] = $returnMessage;
        $this->redirectWithNotifications($this->context->link->getPageLink('order', true, null, array(
            'step' => '4')));
    }

}
