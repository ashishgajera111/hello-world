<?php

class Blockchain_Icanpay_PaymentController extends Mage_Core_Controller_Front_Action
{
    public function successAction()
    {
        Mage::getSingleton('checkout/session')->unsIcanpay();

        $data = explode('-', $_GET['oid']);
        $orderId = $data[0];

        $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);

        $model = Mage::getModel('icanpay/paymentmethod');
        $token = $model->getAccessToken();

        if(isset($token['access_token']) && !empty($token['access_token'])){

            $param = array(
                    "auth_token"        => $token['access_token'],
                    "location_id"       => $model->getLocationId(),
                    "transaction_id"    => $data[1],
                    "phone_number"      => $order->getBillingAddress()->getTelephone(),
                    "gateway"           => '',
		        	"amount"            => $order->getGrandTotal()
                );

            $endpoint = '/api/registrations/virtual_transaction_3ds_success';
            $response = $model->curl($param, $endpoint);

            Mage::log('Transaction Response:'. json_encode($response));

            if(isset($response['success']) && ($response['success'] == true || $response['success'] == 1)){

                $order->setData('state', Mage_Sales_Model_Order::STATE_COMPLETE)->save();

                Mage::getSingleton('checkout/session')->unsQuoteId();
                Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/success', array('_secure' => false));
            }
            else{
                $order->setData('state', Mage_Sales_Model_Order::STATE_CANCELED)->save();
                Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/failure', array('_secure' => false));
            }
        }else{
            $order->setData('state', Mage_Sales_Model_Order::STATE_CANCELED)->save();
            Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/failure', array('_secure' => false));
        }

    }

    public function failureAction()
    {
        Mage::getSingleton('checkout/session')->unsIcanpay();

        $data = explode('-', $_GET['oid']);
        $orderId = $data[0];

        $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);

        $model = Mage::getModel('icanpay/paymentmethod');
        $token = $model->getAccessToken();

        if(isset($token['access_token']) && !empty($token['access_token'])){

            $param = array(
                    "auth_token"        => $token['access_token'],
                    "location_id"       => $model->getLocationId(),
                    "transaction_id"    => $data[1],
                    "phone_number"      => $order->getBillingAddress()->getTelephone(),
                );

            $endpoint = '/api/registrations/virtual_transaction_3ds_failure';
            $response = $model->curl($param, $endpoint);
        }

        $order->setData('state', Mage_Sales_Model_Order::STATE_CANCELED)->save();
        Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/failure', array('_secure' => false));
    }

    public function qcsuccessAction()
    {
        Mage::getSingleton('checkout/session')->unsIcanpay();
        Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/success', array('_secure' => false));
    }
}