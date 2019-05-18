<?php
// app/code/local/Envato/Custompaymentmethod/Block/Form/Custompaymentmethod.php
class Blockchain_Icanpay_Block_Form_Icanpay extends Mage_Payment_Block_Form
{
  protected function _construct()
  {
    parent::_construct();
    $this->setTemplate('blockchain/form/icanpay.phtml');
  }
}