<?php
// app/code/local/Envato/Custompaymentmethod/Block/Info/Custompaymentmethod.php
class Blockchain_Icanpay_Block_Info_Icanpay extends Mage_Payment_Block_Info
{
  protected function _prepareSpecificInformation($transport = null)
  {
    if (null !== $this->_paymentSpecificInformation) 
    {
      return $this->_paymentSpecificInformation;
    }
     
    $data = array();

    if ($this->getInfo()->getCustomCardNumber()) 
    {
      $data[Mage::helper('payment')->__('Card Number')] = $this->getInfo()->getCustomCardNumber();
    }

    if ($this->getInfo()->getCustomCvv()) 
    {
      $data[Mage::helper('payment')->__('CVV')] = $this->getInfo()->getCustomCvv();
    }

    if ($this->getInfo()->getCustomExpiryDate()) 
    {
      $data[Mage::helper('payment')->__('Expiry Date')] = $this->getInfo()->getCustomExpiryDate();
    }

    $transport = parent::_prepareSpecificInformation($transport);
     
    return $transport->setData(array_merge($data, $transport->getData()));
  }
}