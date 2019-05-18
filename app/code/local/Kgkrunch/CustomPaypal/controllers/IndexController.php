<?php

class Kgkrunch_CustomPaypal_IndexController extends Mage_Core_Controller_Front_Action
{    
    
    public function ipnAction(){
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE); //convert JSON into array
        
        Mage::log('START IPN '.$input['invoiceID'].' ---------------------', null, 'moxipay.log');
        Mage::log('Json '.$inputJSON, null, 'moxipay.log');
        //Mage::log('IPN received '.$input, null, 'moxipay.log');
        //Mage::log('IPN invoiceid '.$input['invoiceID'], null, 'moxipay.log');
        if( $input['result'] != 'accepted' ){
            //echo 'result error';
            Mage::log('Order '.$input['invoiceID'].' ERROR ERROR > '.$input['result'], null, 'moxipay.log');
            return;
        }else{
            $order = Mage::getSingleton('sales/order')->load($input['invoiceID']);
            //echo $input['invoiceID'].' >>';
            if( $order->getGrandTotal() != $input['expectedAmount'] ){
                //echo 'AMOUNT DID NOT MATCH';
                Mage::log('AMOUNT ERROR > '.$input['expectedAmount'], null, 'moxipay.log');
            }else{
                if( $order->getStatus() != 'processing' ){
                    $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();
                    $invoice->register();
                    $transactionSave = Mage::getModel('core/resource_transaction')
                    ->addObject($invoice)
                    ->addObject($invoice->getOrder());

                    $transactionSave->save();       
                    $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true)->save();
                    Mage::log('SUCCESS ', null, 'moxipay.log');
                    //echo $input['invoiceID'].' Order Updated';
                    Mage::log('Order '.$input['invoiceID'].' APPROVED > ', null, 'moxipay.log');
                }else{
                    //echo 'State '.$input['invoiceID'].' - '.$order->getStatus();
                }
            }
        }
        
        Mage::log('END IPN '.$input['invoiceID'].' ---------------------', null, 'moxipay.log');

    }
    
    
    public function resendtransferAction(){
    
    	$get = $this->getRequest()->getParams('order_id');
    	$orderid = $get['order_id'];
    	
    	$order = Mage::getModel('sales/order')->load($orderid);
    	
    	$email = $order->getCustomerEmail();
    	$name = $order->getCustomerName();
    	$amount = $order->getGrandTotal();
    	
    	//$email = 'faeez.abdrahman@gmail.com';

    	//die('end');
        Mage::log('Mailing '.$email.' '.$last_order_increment_id, null, 'moxipay.log');
        
        //$to = Mage::getStoreConfig('payment/moxipay/processoremail');
        $to = $email;

        $subject = 'Complete Your Order - Transfer Instructions';

        $headers = "From: ".Mage::getStoreConfig('payment/moxipay/merchantemail')."\r\n";
        $headers .= "Reply-To: ".Mage::getStoreConfig('payment/moxipay/merchantemail')."\r\n";
        //$headers .= "CC: ".Mage::getStoreConfig('payment/moxipay/merchantemail')."\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";    
        
        $message = "<div style='font-size:16px;width: 550px;margin: 0 auto;font-family:Arial;color:#000'>";
        
        $message .= "<p>Dear ".$name."</p>";
        
        $message .= "<p><strong>Send</strong> an <strong>Interac E-transfer</strong> using the following instructions to complete your order:</p>";
        $message .= "<div style='border: 1px solid #999; border-radius:5px;padding:10px;'>";
        $message .= "<p>1. Recipient Email: <strong>pge@emtpay.ca</strong></p>";
        $message .= "<p>2. Amount: <strong>".Mage::helper('core')->currency($amount, true, false)."</strong></p>";
        $message .= "<p>3. In the <strong>Message</strong> section, type your Order Number: <strong>".$orderid."</strong><br> <i>*Please make sure to include this number, so we can match your payment to your order</i>.</p>";
        $message .= "</div>";
        $message .= "<p>If you are asked to provide a security question and answer, please enter:</p>";
        $message .= "<p>Secret Question:  <strong>“What's our Country?”</strong></p>";
        $message .= "<p>Answer:  <strong>Canada</strong></p>";

        $message .= "<p>Once payment is received, your order will automatically be updated to “Processing” status. You will receive your tracking number within 24 hours and can expect to receive your order within 3-4 days. </p>";
        $message .= "<p>Thanks for being a great customer! </p>";
        $message .= "<div style='text-align:center'>";
        $message .= "<img src='https://www.puregreenexpress.ca/skin/frontend/default/theme292k/images/logo_square.png' />";
        $message .= "</div>";         
        $message .= "</div>";
        
        mail($to, $subject, $message, $headers);
        Mage::getSingleton('adminhtml/session')->addSuccess('my message goes here');
        Mage::getSingleton('core/session')->addSuccess('my message goes here');
        //return;
        //session_write_close();
        Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("adminhtml/sales_order/view", array('order_id'=>$orderid )));   
       	//return $this;
    }    
   
}

?>