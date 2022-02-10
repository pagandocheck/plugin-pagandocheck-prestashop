<?php

// La url también está en el archivo de prestashop-payment-module/pagandopayment/pagandopayment.php
const BASE_URI = 'https://api.pagandocheck.com/v1/';
const API_URI = 'https://api.pagandocheck.com/v1/pagando/';
const CHECKOUT_URI = 'https://checkout.pagandocheck.com/';

class Pagando
{
    public $error_msg, $error, $id;
    protected $baseURI = BASE_URI;
    protected $apiURI = API_URI;
    protected $checkoutURI = CHECKOUT_URI;
    protected $api_user,
        $api_pass,
        $token,
        $user_id,
        $card_id,
        $cart,
        $cartId,
        $card,
        $promotion,
        $amount,
        $customer,
        $address,
        $delivery,
        $state,
        $payment_concept,
        $order_id,
        $aft_token,
        $card_name,
        $card_no,
        $card_cvv,
        $card_month,
        $card_year;

    function __construct($user, $pass, $order_params = null, $processPayment = false) {
      	$this->api_user = $user;
        $this->api_pass = $pass;
      	if(!empty($order_params['cart'])){
        	$cart = $order_params['cart'];
        };
      	if(!empty($order_params['card'])){
        	$card = $order_params['card'];
        };
      	if(!empty($order_params['promotion'])){
        	$promotion = $order_params['promotion'];
        };
      	if(!empty($order_params['payment_concept'])){
          //$payment_concept = $order_params['api_concept'];
	        $payment_concept = $order_params['payment_concept'];
        };
      	if(!empty($order_params['aft_token'])){
        	$this->aft_token = $order_params['aft_token'];
        };
        if(!empty($promotion)){
      		$this->promotion = $promotion;
        };
      	if(!empty($card)){
          $this->card = $card;
          $this->card_name = $card["card_name"];
          $this->card_no = $card["card_no"];
          $this->card_cvv =$card["cvv"];
          $this->card_month = $card["month"];
          $this->card_year = $card["year"];
        };
        if (!empty($cart)) {
        	$this->cart = $cart;
            $this->setData($cart);
        	$this->cartId = $cart->id;
        }
      	if(!empty($payment_concept)){
        	$this->payment_concept = $payment_concept;
        };
        if($processPayment) {
            $this->process();
        }
    }

    function setData($cart){
        $this->customer = new Customer($cart->id_customer);
        $this->address = new Address($cart->id_address_invoice);
        $this->delivery = new Address($cart->id_address_delivery);
        $this->state = new State($this->address->id_state);
        $this->amount = (float)$cart->getOrderTotal(true, Cart::BOTH);
    }

    function process(){
        if ($this->cart->id_currency != 'MXN') {
            $this->error_msg = 'Pagos en '.$this->cart->id_currency.' no están soportados.';
        }

        $userdata = $this->getUserData();
        $this->getToken();
        $this->createEcommerceOrder();

        if(!empty($this->token) && !empty($this->order_id)){
            $this->addUser($userdata);
            if(!empty($this->user_id)){
                $this->addCard($this->card);
                if(!empty($this->card_id)){
                    $resOrder = $this->orderCreate();
                    if($resOrder->error){
                        return ['error'=>1, 'msg' => $resOrder->message];
                    }
                    $get_data = (Object)[
                        'orderId' => $this->order_id,
                    ];
                    $this->getEcommerceOrderData($get_data);
                }
            }
        }
        return ['error'=>1, 'msg' => $this->error_msg];
    }

    protected function getEcommerceData() {
        $customer = $this->customer;
        $address = $this->address;
        $delivery = $this->delivery;
        $tmptState = new State($delivery->id_state);

        $data['email'] = $customer->email;
        $data['name'] = $customer->firstname;
        $data['lastName'] = $customer->lastname;
        $data['birthday'] = $customer->birthday;
        $data['phone'] = $address->phone;
        $data['street'] = $address->address1;
        $data['zipCode'] = $address->postcode;
        $data['city'] = $address->city;
        $data['state'] = $this->state->name;
        $data['country'] = $address->country;
        $data['cartId'] = $this->cartId;
        $data['total'] = $this->amount;
        $data['paymentToken'] = md5(Configuration::get('PAGANDO_PASSWORD').$data['cartId'].$data['total']);;
        $data['originECommerce'] = 'PRESTASHOP';
        $data['productsList'] = array();

        // Get delivery information
        $shippingInfo['street'] = $delivery->address1;
        $shippingInfo['noExt'] = '11';
        $shippingInfo['district'] = $delivery->address2;
        $shippingInfo['zipCode'] = $delivery->postcode;
        $shippingInfo['city'] = $delivery->city;
        $shippingInfo['state'] = $tmptState->name;
        $shippingInfo['country'] = $delivery->country;

        $data['shippingInfo'] = $shippingInfo;

        // Choose only necessary data from products
        foreach ($this->cart->getProducts(true) as $item) {

            $tempItem['quantity'] = $item["cart_quantity"];
            $tempItem['productSku'] = $item["id_product"];
            $tempItem['productName'] = $item["name"];
            $tempItem['productType'] = $item["category"];
            $tempItem['unitPrice'] = $item["price_wt"];
            $tempItem['totalAmount'] = $item["total_wt"];

            array_push($data['productsList'], $tempItem);
        }

        // Adding shipping cost to productsList
        $shipItem['quantity'] = 1;
        $shipItem['productSku'] = 'SHIP';
        $shipItem['productName'] = 'SHIPPING_COST';
        $shipItem['productType'] = 'SHIPPING_COST';
        $shipItem['unitPrice'] = $this->cart->getPackageShippingCost();
        $shipItem['totalAmount'] = $this->cart->getPackageShippingCost();;

        array_push($data['productsList'], $shipItem);

        return $data;

    }

    protected function getUserData(){
        $user['email'] = $this->customer->email;
        $user['name'] = $this->customer->firstname;
        $user['lastName'] = $this->customer->lastname;
        if ($this->customer->birthday != null && $this->customer->birthday->length > 0) {
            $user['birthday'] = $this->customer->birthday;
        }
        $user['phone'] = $this->address->phone;

        return $user;
    }

    function getToken(){
        $data = [
            'user' => $this->api_user,
            'password' => $this->api_pass
        ];
        $res = $this->post('get-token', $data);

        if (!$res->error) {
            $this->token = $res->data->token;
            return $this->token;
        }

        return $res->error;
    }

    function addUser($data){
        $res = $this->post('users/user', $data);

        if(!$res->error) {
            $this->user_id = $res->data->userId;
        }

        return $res->error;
    }

    function addCard($data){
        $data['userId'] = $this->user_id;
        $res = $this->post('payment_methods/add_card', $data);

        if(!$res->error) {
            $this->card_id = $res->data->cardId;
        }

        return $res->error;
    }

    function orderCreate()
    {

        $data['userId']   = $this->user_id;
        $data['cardId']   = $this->card_id;
        $data['aftToken'] = $this->aft_token;
        $data['pin']      = $this->card_cvv;
        $data['amount']   = $this->amount;
        $data['concept']  = $this->payment_concept;
        $data['orderId']  = $this->order_id;
        $data['street'] = $this->address->address1;
        $data['district'] = $this->address->address2;
        $data['zipCode'] = $this->address->postcode;
        $data['city'] = $this->address->city;
        $data['state'] = $this->state->name;
        $data['country'] = $this->address->country;
        $data['items']  = array();

        // Choose only necessary data from products
        foreach ($this->cart->getProducts(true) as $item) {

            $tempItem['quantity'] = $item["cart_quantity"];
            $tempItem['productSku'] = $item["id_product"];
            $tempItem['productName'] = $item["name"];
            $tempItem['productType'] = $item["category"];
            $tempItem['unitPrice'] = $item["price_wt"];
            $tempItem['totalAmount'] = $item["total_wt"];

            array_push($data['items'], $tempItem);
        }

        // Adding shipping cost to productsList
        $shipItem['quantity'] = 1;
        $shipItem['productSku'] = 'SHIP';
        $shipItem['productName'] = 'SHIPPING_COST';
        $shipItem['productType'] = 'SHIPPING_COST';
        $shipItem['unitPrice'] = $this->cart->getPackageShippingCost();
        $shipItem['totalAmount'] = $this->cart->getPackageShippingCost();;

        array_push($data['items'], $shipItem);

        if ($this->promotion['promotionType'] != null) {
            $data['paymentPromotion'] = $this->promotion;
        }

        $res = $this->post('orders/create-order', $data);

        if(!$res->error){
            $this->id = $res->data->folio;
        }

        return $res;
    }

    function createEcommerceOrder() {
        // Get service params
        $data = $this->getEcommerceData();

        $res = $this->post('orders/create-ecommerce-order', $data);

        print_r($res);

        if(!$res->error) {
            $this->order_id = $res->data->orderId;
            return $res->data->orderId;
        }

        return $res->error;
    }

    function getRedirectURIForPagandoCheckout($orderId, $mode) {
        return $this->checkoutURI."_external-payment?orderId=".$orderId."&token=".$this->token."&mode=".$mode;
    }

    function getEcommerceOrderData($data) {
        $res = $this->request('orders/get-order-info?orderId='.$data->orderId, $data);

        if(!$res->error) {
            return $res->data;
        }

        return $res->error;
    }

    function post($path, $data)
    {
        return $this->request($path, $data, "POST");
    }

    function request( $path, $data = [], $type = "GET", $uri = API_URI )
    {
        $url = $uri.$path;

        $headers[] = "Content-Type: application/x-www-form-urlencoded";

        if(!empty($this->token)){
            $headers[] = "Authorization: ".$this->token;
        }

        $settings = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => $headers,
        );

        if($type != "GET")
        {
            $settings[CURLOPT_CUSTOMREQUEST] = $type;

            if(!empty($data)){
                $settings[CURLOPT_POSTFIELDS] = http_build_query($data);
            }
        }

        $curl = curl_init();
        curl_setopt_array($curl, $settings);
        $response = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($response);

        if ($result !== null && $result->message !== null) {
            $this->error_msg = $result->message;
        } else {
            $this->error_msg = 'Ocurrió un error inesperado';
        }

        $return = new stdClass();

        if ($result->error) {
            $this->error = true;
            $return->error = true;
            $return->message = $result->message;
        } else if(!empty($result->data)){
            $this->error = false;
            $return->error = false;
            $return->data = $result->data;
        } else if(!empty($result->object)){
            $this->error = false;
            $return->error = false;
            $return->data = $result->object;
        }

        return $return;
    }
}
