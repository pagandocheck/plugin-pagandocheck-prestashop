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

use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

include_once _PS_MODULE_DIR_.'pagandopayment/core/Pagando.php';

if (!defined('_PS_VERSION_')) {
    exit;
}

class PagandoPayment extends PaymentModule
{
    protected $_html = '';
    protected $_postErrors = array();

    public $details;
    public $owner;
    public $address;
    public $extra_mail_vars;

    public function __construct()
    {
        $this->name = 'pagandopayment';
        $this->tab = 'payments_gateways';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->author = 'Black Labs';
        $this->controllers = array('validation');
        $this->is_eu_compatible = 1;

        $this->currencies = true;
        $this->currencies_mode = 'checkbox';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Pagando Check');
        $this->description = $this->l('Procesamiento de pagos a través de Pagando Check');

        if (!count(Currency::checkPaymentCurrencies($this->id))) {
            $this->warning = $this->l('No currency has been set for this module.');
        }
    }

    public function install()
    {
        if (!parent::install() || !$this->registerHook('paymentOptions') || !$this->registerHook('paymentReturn')) {
            return false;
        }
        return true;
    }

    public function hookPaymentOptions($params)
    {
        if (!$this->active) {
            return;
        }

        if (!$this->checkCurrency($params['cart'])) {
            return;
        }

        $payment_options = [];

        if ($this->getConfigFieldsValues()['PAYMENT_METHODS_ALLOWED_GUEST']) {
            array_push($payment_options, $this->getExternalPaymentGuestOption());
        }
        if ($this->getConfigFieldsValues()['PAYMENT_METHODS_ALLOWED_EMBEDDED']) {
            array_push($payment_options, $this->getEmbeddedPaymentOption($params['cart']));
        }

        return $payment_options;
    }

    public function getEmbeddedPaymentOption($cart)
    {
        $embeddedOption = new PaymentOption();
        $embeddedOption->setCallToActionText($this->l('Pagar con Pagando Check'))
                    ->setForm($this->generateForm($cart))
                    ->setLogo(Media::getMediaPath(_PS_MODULE_DIR_.$this->name.'/pagando-logo-horizontal.svg'));

        return $embeddedOption;
    }

    public function checkCurrency($cart)
    {
        $currency_order = new Currency($cart->id_currency);
        $currencies_module = $this->getCurrency($cart->id_currency);

        if (is_array($currencies_module)) {
            foreach ($currencies_module as $currency_module) {
                if ($currency_order->id == $currency_module['id_currency']) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getExternalPaymentOption()
    {
        $externalOption = new PaymentOption();
        $externalOption->setCallToActionText($this->l('Pagar con tarjeta de crédito o débito'))
            ->setAction($this->context->link->getModuleLink($this->name, 'middleware', array('mode' => 'user'), true))
            ->setAdditionalInformation($this->context->smarty->fetch('module:pagandopayment/views/templates/front/pagando_checkout_info.tpl'));

        return $externalOption;
    }

    public function getExternalPaymentGuestOption()
    {
        $externalOption = new PaymentOption();
        $externalOption->setCallToActionText($this->l('Pagar con tarjeta de crédito o débito'))
            ->setAction($this->context->link->getModuleLink($this->name, 'middleware', array(), true))
            ->setAdditionalInformation($this->context->smarty->fetch('module:pagandopayment/views/templates/front/pagando_checkout_guest_info.tpl'));

        return $externalOption;
    }

    protected function generateForm($cart)
    {
        $api_user = Configuration::get('PAGANDO_USER');
        $api_pass = Configuration::get('PAGANDO_PASSWORD');
        $apiURI = 'https://api.pagandocheck.com/v1/';
        $pagando = new Pagando($api_user, $api_pass);
        $countries_response = $pagando->request('countries/countries', null, null, $apiURI);
        $token = $pagando->getToken();
        $amount = (float)$cart->getOrderTotal(true, Cart::BOTH);

        $this->context->smarty->assign([
            'action' => $this->context->link->getModuleLink($this->name, 'validation', array(), true),
            'countries' => $countries_response->data,
            'uid' => Configuration::get('PAGANDO_USER'),
            'apiURI' => $apiURI,
            'token' => $token,
            'amount' => $amount,
        ]);

        return $this->context->smarty->fetch('module:pagandopayment/views/templates/front/payment_form.tpl');
    }

    /**
     * Settings
     */
    public function uninstall()
    {
        return Configuration::deleteByName('PAGANDO_USER')
            && Configuration::deleteByName('PAGANDO_PASSWORD')
            && parent::uninstall()
        ;
    }

    private function _postValidation()
    {
        if (Tools::isSubmit('btnSubmit')) {
            if (!Tools::getValue('PAGANDO_USER')) {
                $this->_postErrors[] = $this->trans('The "Payee" field is required.', array(),'Modules.Paymentexample.Admin');
            } elseif (!Tools::getValue('PAGANDO_PASSWORD')) {
                $this->_postErrors[] = $this->trans('The "Address" field is required.', array(), 'Modules.Paymentexample.Admin');
            }
        }
    }

    private function _postProcess()
    {
        if (Tools::isSubmit('btnSubmit')) {
            Configuration::updateValue('PAGANDO_USER', Tools::getValue('PAGANDO_USER'));
            Configuration::updateValue('PAGANDO_PASSWORD', Tools::getValue('PAGANDO_PASSWORD'));
            Configuration::updateValue('PAYMENT_MODE', Tools::getValue('PAYMENT_MODE'));
            Configuration::updateValue('PAYMENT_CONCEPT', Tools::getValue('PAYMENT_CONCEPT'));
            Configuration::updateValue('PAYMENT_METHODS_ALLOWED_EMBEDDED', Tools::getValue('PAYMENT_METHODS_ALLOWED_EMBEDDED'));
            Configuration::updateValue('PAYMENT_METHODS_ALLOWED_GUEST', Tools::getValue('PAYMENT_METHODS_ALLOWED_GUEST'));
        }
        $this->_html .= $this->displayConfirmation($this->trans('Settings updated', array(), 'Admin.Notifications.Success'));
    }

    private function _displayCheck()
    {
        return $this->display(__FILE__, './views/templates/hook/infos.tpl');
    }

    public function getContent()
    {
        $this->_html = '';

        if (Tools::isSubmit('btnSubmit')) {
            $this->_postValidation();
            if (!count($this->_postErrors)) {
                $this->_postProcess();
            } else {
                foreach ($this->_postErrors as $err) {
                    $this->_html .= $this->displayError($err);
                }
            }
        }

        // $this->_html .= $this->_displayCheck();
        $this->_html .= $this->renderForm();

        return $this->_html;
    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->trans('PAGANDO SETTINGS', array(), 'Modules.Paymentexample.Admin'),
                    'icon' => 'icon-envelope'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->trans('User', array(), 'Modules.Paymentexample.Admin'),
                        'name' => 'PAGANDO_USER',
                        'required' => true
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans('Password', array(), 'Modules.Paymentexample.Admin'),
                        // 'desc' => $this->trans('Address where the check should be sent to.', array(), 'Modules.Paymentexample.Admin'),
                        'name' => 'PAGANDO_PASSWORD',
                        'required' => true
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans('Payment concept', array(), 'Modules.Paymentexample.Admin'),
                        'desc' => $this->trans('This is the concept that your clients will see in their accounts. If empty we will use the name of your organization in Pagando', array(), 'Modules.Paymentexample.Admin'),
                        'name' => 'PAYMENT_CONCEPT',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->trans('Guest payment', array(), 'Modules.Paymentexample.Admin'),
                        'desc' => $this->trans('Allow guest payment', array(), 'Modules.Paymentexample.Admin'),
                        'name' => 'GUEST_PAYMENT_ALLOW',
                    ),
                    array(
                        'type' => 'checkbox',
                        'label' => $this->trans('Payment methods', array(), 'Modules.Paymentexample.Admin'),
                        'desc' => $this->trans('Payment methods allowed during checkout', array(), 'Modules.Paymentexample.Admin'),
                        'name' => 'PAYMENT_METHODS_ALLOWED',
                        'values'  => array(
                            'query' => array(
                                array (
                                    'id' => 'EMBEDDED',
                                    'name' => 'Embedded'
                                ),
                                array (
                                    'id' => 'GUEST',
                                    'name' => 'Guest'
                                ),
                            ),
                            'id'    => 'id',
                            'name'  => 'name'
                        )
                    ),
                ),
                'submit' => array(
                    'title' => $this->trans('Save', array(), 'Admin.Actions'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->id = (int)Tools::getValue('id_carrier');
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'btnSubmit';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
        );

        $this->fields_form = array();

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        return array(
            'PAGANDO_USER' => Tools::getValue('PAGANDO_USER', Configuration::get('PAGANDO_USER')),
            'PAGANDO_PASSWORD' => Tools::getValue('PAGANDO_PASSWORD', Configuration::get('PAGANDO_PASSWORD')),
            'PAYMENT_MODE' => Tools::getValue('PAYMENT_MODE', Configuration::get('PAYMENT_MODE')),
            'PAYMENT_CONCEPT' => Tools::getValue('PAYMENT_CONCEPT', Configuration::get('PAYMENT_CONCEPT')),
            'PAYMENT_CONCEPT' => Tools::getValue('PAYMENT_CONCEPT', Configuration::get('PAYMENT_CONCEPT')),
            'PAYMENT_METHODS_ALLOWED_EMBEDDED' => Tools::getValue('PAYMENT_METHODS_ALLOWED_EMBEDDED', Configuration::get('PAYMENT_METHODS_ALLOWED_EMBEDDED')),
            'PAYMENT_METHODS_ALLOWED_GUEST' => Tools::getValue('PAYMENT_METHODS_ALLOWED_GUEST', Configuration::get('PAYMENT_METHODS_ALLOWED_GUEST')),
        );
    }
}
