<?php

class Kgkrunch_CustomPaypal_Model_Paymentmethod extends Mage_Payment_Model_Method_Abstract {
    protected $_code  = 'custompaypal';
    protected $_formBlockType = 'custompaypal/form';

    public function getOrderPlaceRedirectUrl()
    {
        $this->prepareRequest();
        //return Mage::getUrl('custompaymentmethod/payment/redirect', array('_secure' => false));
    }
    
   public function prepareRequest(){
            //$this->sendRequest();
    }

    public function sendRequest(){
        $orderid = Mage::getModel('sales/order')->getCollection()->setOrder('created_at','DESC')->setPageSize(1)->setCurPage(1)->getFirstItem()->getEntityId();
        $order = Mage::getSingleton('sales/order')->load($orderid);
        $this->sendmail($order->getIncrementId(), $order->getBillingAddress()->getEmail(), $order->getBillingAddress()->getFirstname(), $order->getGrandTotal());
        return;
    }   
    
    public function sendmail($orderid, $email, $name, $amount){
        
        //$to = Mage::getStoreConfig('payment/moxipay/processoremail');
        $to = $email;
        $subject = 'Complete Your Order - Paypal Instructions';
        $headers = "From: ".Mage::getStoreConfig('trans_email/ident_sales/email')."\r\n";
        $headers .= "Reply-To: ".Mage::getStoreConfig('trans_email/ident_sales/email')."\r\n";
        //$headers .= "CC: ".Mage::getStoreConfig('payment/moxipay/merchantemail')."\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";    
        $message = "<div style='font-size:16px;width: 550px;margin: 0 auto;font-family:Arial;color:#000'>";
        $message .= "<p>Dear <b>".$name.",</b></p>";
        $message .= "<p>Complete your order using the instructions below to send your <strong>Paypal</strong> payment:</p>";
        $message .= "<div style='border: 1px solid #999; border-radius:5px;padding:10px;'>";
        $message .= "<p>1. Send payment to: <strong>pge@purepay1.com</strong></p>";
        $message .= "<p>2. Amount: <strong>".Mage::helper('core')->currency($amount, true, false)."</strong></p>";
        $message .= "<p>3. In the <strong>Message</strong> section, type your Order Number: <strong>".$orderid."</strong><br> <i>*Please make sure to include this number, so we can match your payment to your order</i>.</p>";
        $message .= "</div>";
        $message .= '<p>Once payment is received, your order will automatically be updated to "Processing" status. You will receive your tracking number within 24 hours and can expect to receive your order within 3-4 days. </p>';
        $message .= "If you have any questions about your order, simply Reply to this email or contact us via live chat box on our website. We are always happy to hear from you.";
		$message .= "<p>Thanks for being a great customer! </p>";
        $message .= "<div style='text-align:center'>";
        $message .= "<img src='https://www.puregreenexpress.ca/skin/frontend/default/theme292k/images/logo_square.png' alt='Pure Green Express' />";
        $message .= "</div>";         
        $message .= "</div>";
	
		//Sent mail instant date :- 23/01/2019 by kgkrunch
		$mail = Mage::getModel('core/email')
				 ->setToEmail($email)
				 ->setBody($message)
				 ->setSubject($subject)
				 ->setFromName('PGE Support')
				 ->setReplyTo(Mage::getStoreConfig('trans_email/ident_sales/email'))
				 ->setFromEmail(Mage::getStoreConfig('trans_email/ident_sales/email'))
				 ->setType('html');
							 
		$mail->send(); 
		//Sent mail instant date :- 23/01/2019 by kgkrunch
        
        //mail($to, $subject, $message, $headers);
        return;
    }
}