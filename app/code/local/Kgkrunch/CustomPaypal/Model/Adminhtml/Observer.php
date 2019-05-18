<?php 
class Kgkrunch_CustomMoxipay_Model_Adminhtml_Observer 
{
    public function addEtransferButton($observer) {
        $block = Mage::app()->getLayout()->getBlock('sales_order_edit');
        if (!$block){
            return $this;
        }
        $order = Mage::registry('current_order');
	//echo $order->getStatusLabel();
	//echo $order->getPayment()->getMethodInstance()->getCode();
        $url = Mage::helper('adminhtml')->getUrl("adminhtml/sales_order/view", array('order_id'=>$order->getId(),'resendetransfer'=>'1'));
        //if( $order->getStatusLabel() != 'Canceled' && $order->getStatusLabel() != 'Complete' && $order->getStatusLabel() != 'Closed' && $order->getPayment()->getMethodInstance()->getCode() == 'moxipay' ){
	        $block->addButton('etransfer_resend', array(
	                'label'     => Mage::helper('sales')->__('Resend Etransfer Email'),
	                'onclick'   => 'setLocation(\'' . $url . '\')',
	                'class'     => 'resend'
	        ));
	//}

        return $this;
    }
    public function resendEtransferEmail($observer) {
    	$get = Mage::app()->getRequest()->getParams();
    	if( isset($get['resendetransfer']))
		{
			$orderid = $get['order_id'];    	
    		
	    	$order = Mage::getModel('sales/order')->load($orderid);
	    	
	    	$email = $order->getCustomerEmail();
	    	$name = $order->getCustomerName();
			
			$billingAddress = $order->getBillingAddress();
     		$firstname = $billingAddress->getFirstname();
			
	    	$amount = $order->getGrandTotal();
	    	$last_order_increment_id = $order->getIncrementId();
	        Mage::log('Mailing Resend '.$email.' '.$last_order_increment_id, null, 'moxipay.log');
	        
	        //$to = Mage::getStoreConfig('payment/moxipay/processoremail');
	        $to = $email;
	
	        $subject = 'Complete Your Order - Transfer Instructions';
	
	        $headers = "From: ".Mage::getStoreConfig('payment/custommoxipay/merchantemail')."\r\n";
	        $headers .= "Reply-To: ".Mage::getStoreConfig('payment/custommoxipay/merchantemail')."\r\n";
	        //$headers .= "CC: ".Mage::getStoreConfig('payment/moxipay/merchantemail')."\r\n";
	        $headers .= "MIME-Version: 1.0\r\n";
	        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";    
	        
	        $message = "<div style='font-size:16px;width: 550px;margin: 0 auto;font-family:Arial;color:#000'>";
	        
	        $message .= "<p>Dear <b>".$firstname.",</b></p>";
	        
	        $message .= "<p>Complete your order using the instructions below to send your <strong>e-transfer</strong> payment:</p>";
	        $message .= "<div style='border: 1px solid #999; border-radius:5px;padding:10px;'>";
	        $message .= "<p>1. Recipient Email: <strong>pge@purepay1.com</strong></p>";
	        $message .= "<p>2. Amount: <strong>".Mage::helper('core')->currency($amount, true, false)."</strong></p>";
	        $message .= "<p>3. In the <strong>Message</strong> section, type your Order Number: <strong>".$last_order_increment_id ."</strong><br> <i>*Please make sure to include this number, so we can match your payment to your order</i>.</p>";
	        $message .= "</div>";
	        $message .= "<p>If you are asked to provide a security question and answer, please enter:</p>";
	        $message .= '<p>Secret Question:  <strong>"What\'s our Country?"</strong></p>';
	        $message .= "<p>Answer:  <strong>Canada</strong></p>";
	
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
				 ->setReplyTo(Mage::getStoreConfig('payment/custommoxipay/merchantemail'))
				 ->setFromEmail(Mage::getStoreConfig('payment/custommoxipay/merchantemail'))
				 ->setType('html');
				 
			$mail->send();
			//Sent mail instant date :- 23/01/2019 by kgkrunch 
			
			Mage::getSingleton('adminhtml/session')->addSuccess('Etransfer email has been sent.');
	        
	        /*if(mail($to, $subject, $message, $headers)){
	        	Mage::getSingleton('adminhtml/session')->addSuccess('Etransfer email has been sent.');
	        	//mail('processing@moxipay.com', $subject, $message, $headers);
	        	//mail('support@puregreenexpress.ca', $subject, $message, $headers);
	        } */   	
    		
    	}
    	return $this;
    }
}
