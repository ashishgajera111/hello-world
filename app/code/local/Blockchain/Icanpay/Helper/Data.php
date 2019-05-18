<?php
// app/code/local/Envato/Custompaymentmethod/Helper/Data.php
class Blockchain_Icanpay_Helper_Data extends Mage_Core_Helper_Abstract
{
  function getPaymentGatewayUrl() 
  {
    return Mage::getUrl('icanpay/payment/gateway', array('_secure' => false));
  }
}