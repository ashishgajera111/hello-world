<?php
class Debitway_Debitcard_Model_Debitcard extends Mage_Payment_Model_Method_Cc {
    protected $_code = 'debitcard';


    protected $_formBlockType = 'debitcard/form_debitcard';
    protected $_infoBlockType = 'debitcard/info_debitcard';
    protected $_isGateway = true;
    protected $_canAuthorize = true;
    protected $_canCapture = true;
    protected $_canRefund = true;
    protected $_canSaveCc = false;
    protected $_canVoid = true;
 
    protected $_canUseInternal          = true;
    protected $_canUseCheckout          = true;
    protected $_canUseForMultishipping  = false;
    protected $_canRefundInvoicePartial = true;


    
    public function process($data) {
        if ($data['cancel'] == 1) {
            $order->getPayment()->setTransactionId(null)->setParentTransactionId(time())->void();
            $message = 'Unable to process Payment';
            $order->registerCancellation($message)->save();
        }
    }

    /** For void **/
    public function void(Varien_Object $payment)
    {
        
      
       $result = $this->callDecline($payment, 'Decline');
        
        
         if ($result === false) {
            $errorCode = 'Invalid Data';
            $errorMsg  = $this->_getHelper()->__('Error Processing the request');
            Mage::throwException($errorMsg);
        }

        return $this;
       
    }

    /** For cancel **/
    public function cancel(Varien_Object $payment)
    {
        
        $amount = 0;
        $result = $this->callDecline($payment, 'Decline');
        
        
         if ($result === false) {
            $errorCode = 'Invalid Data';
            $errorMsg  = $this->_getHelper()->__('Error Processing the request');
            Mage::throwException($errorMsg);
        }

        return $this;
    }
    
    /** For capture **/
    public function capture(Varien_Object $payment, $amount) {
		
		$amount = number_format($amount,2);
	   /*$helper = Mage::helper('directory');
       $amount1 = $helper->currencyConvert($amount, 'CAD', 'USD');
	   $amount = number_format($amount1,2);*/
	   
        $order  = $payment->getOrder();

        $transaction_id = $payment->getParentTransactionId();

        if($transaction_id==null){
          

             $result = $this->callApi($payment, $amount, 'Sale');
        }
        else{
          
           
             $result = $this->callApi($payment, $amount, 'Saleadmin');
        }
       

        $errorMsg = false;
        if ($result === false) {
            $errorCode = 'Invalid Data';
            $errorMsg  = $this->_getHelper()->__('Error Processing the request');
        } 
        else {
            if ($result['status'] == 1) {


                $payment->setTransactionId($result['transaction_id']);
                $payment->setIsTransactionClosed(1);

                // Save additional variables as needed
                $payment->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, array(
                    'key1' => 'value1',
                    'key2' => 'value2'
                ));
            }
            else {

               

               Mage::throwException("Error Encountered: ".$result['error']);
            }
        }

        if ($errorMsg) {
            Mage::throwException($errorMsg);
        }

        return $this;
    }

    
    
    /** For authorization **/
    public function authorize(Varien_Object $payment, $amount) {

       //Mage::throwException("in authorize");
        $order    = $payment->getOrder();
        $items    = $order->getAllVisibleItems();

        $result   = $this->callApi($payment, $amount, 'Authorization');
        $errorMsg = "";

        if ($result === false) {
            $errorCode = 'Invalid Data';
            $errorMsg  = $this->_getHelper()->__('Error Processing the request');
            Mage::throwException($errorMsg);
        } 
        else {            
            if ($result['status'] == 1) {

                $payment->setTransactionId($result['transaction_id']);
                $payment->setIsTransactionClosed(false);
                $payment->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, array(
                    'key1' => 'value1',
                    'key2' => 'value2'
                ));
                
                $order->addStatusToHistory($order->getStatus(), 'Payment Sucessfully Placed with Transaction ID ' . $result['transaction_id'], false);
                $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true)->save();
               

            } 
            else if($result['status'] == 0){

              
                Mage::throwException("Error Encountered: ".$result['error']);
                           
            }
        }
        
        return $this;
    }
    
    public function processBeforeRefund($invoice, $payment)
    {
        return parent::processBeforeRefund($invoice, $payment);
    }

    public function refund(Varien_Object $payment, $amount)
    {

        $order  = $payment->getOrder();
        $result = $this->callApi($payment, $amount, 'Refund');
        if ($result === false) {
            $errorCode = 'Invalid Data';
            $errorMsg  = $this->_getHelper()->__('Error Processing the request');
            Mage::throwException($errorMsg);
        }

        return $this;    
    }

    public function processCreditmemo($creditmemo, $payment)
    {
        return parent::processCreditmemo($creditmemo, $payment);
    }
    
    /**
     *
     *  @param (type) This string can be used to tell the API
     *               if the call is for Sale or Authorization
     */
    private function callApi(Varien_Object $payment, $amount, $type = "")
    {
        
        $identifier = trim(Mage::getStoreConfig('payment/debitcard/identifier'));
        $vericode =  trim(Mage::getStoreConfig('payment/debitcard/vericode'));
        $website_unique_id =  trim(Mage::getStoreConfig('payment/debitcard/website_unique_id'));
        $url =  trim(Mage::getStoreConfig('payment/debitcard/integration_url'));


       
        if ($amount > 0) {

           
			$order            = $payment->getOrder();
		    $cc_number        = $payment->getCcNumber();
			$expirationMonth   = $payment->getCcExpMonth();
		    $expirationYear   = $payment->getCcExpYear();
		    $billingAddress   = $order->getBillingAddress();
            $shippingAddress  = $order->getShippingAddress();
		    $street           = $billingAddress->getStreet(1);
		    $postcode         = $billingAddress->getPostcode();
		    $cc_security_code = $payment->getCcCid();
          
            if(strlen($expirationMonth)==1){
                $expirationMonth = '0'.$expirationMonth;
            }

            $cc_expdate = substr($expirationYear,-2).$expirationMonth;
            $cc_type = trim($payment->getCcType());
            if($cc_type == 'VI'){
                $cc_type = 'VISA';

            }else if($cc_type == 'MC'){

                $cc_type = 'MASTERCARD';
            }


            $items = $order->getAllItems();
             
            $item_name_total ="";
            foreach($items as $item) {
                $qty = round($item->getData('qty_ordered'));
                $name = $item->getName();
                $item_total = $qty.'*'.$name;
                if($item_name_total!=null){
                    $item_name_total .='-';
                }
                $item_name_total .=$item_total;

            }
            $grandTotal1 = number_format($order->getGrandTotal(),2);
			
			///////////////////////////////////////////////////////////
			//$helper = Mage::helper('directory');
			//$amount1 = $helper->currencyConvert($grandTotal1, 'CAD', 'USD');
			//$grandTotal1 = number_format($amount1,2);
			///////////////////////////////////////////////////////////

            $first_name = Mage::helper('core')->removeAccents($billingAddress->getFirstname());
            $last_name  = Mage::helper('core')->removeAccents($billingAddress->getLastname());
            $phone = $order->getCustomerTelephone();
            $email = $order->getCustomerEmail();

            $billing_address = $billingAddress->getStreet1();
            $billing_city = $billingAddress->getCity();           
            $billing_state_or_province = $billingAddress->getRegionCode();
            $billing_country = $billingAddress->getCountry();
            $billing_zip_or_postal_code = $billingAddress->getPostcode();
            $bphone = $billingAddress->getTelephone();

            $shipping_address = $shippingAddress->getStreet1();
            $shipping_city = $shippingAddress->getCity();           
            $shipping_state_or_province = $shippingAddress->getRegionCode();
            $shipping_country = $shippingAddress->getCountry();
            $shipping_zip_or_postal_code = $shippingAddress->getPostcode();
            $return_url = Mage::getBaseUrl (Mage_Core_Model_Store::URL_TYPE_WEB); 
            $return_url .= 'pay/payment/response';

            if($type =="Refund")
			{

                $transaction_id  = $payment->getParentTransactionId();
                if($transaction_id==null){
                    Mage::throwException(Mage::helper('payment')->__('null value in transaction id'));
                
                }
                
         
                $comments = "A refund was issued to the customer";
                $ch = curl_init();
               
                curl_setopt($ch, CURLOPT_URL,$url);
            
                curl_setopt($ch, CURLOPT_POST, true);

                $post_data = 'identifier='.$identifier.'&vericode='.$vericode.'&comments='.$comments.'&transaction_id='.$transaction_id.'&amount='.$amount.'&action=refund';
                curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                $result = curl_exec($ch);
				
				Mage::log('Main Output >>>>  :- '.$result,null,'debitway_custom.log',true);	

                curl_close($ch);
               
                $output = $this->get_value_from_response($result,'result');
				
				Mage::log('Result >>>>  :- '.$output,null,'debitway_custom.log',true);	
				
                if($output == "success"){
                      $approved = true;
                }else{
                     $error = $this->get_value_from_response($result,'errors_meaning');
					 Mage::log('Error >>>>  :- '.$error,null,'debitway_custom.log',true);	
                    $approved = false;
                    Mage::throwException($error);
                }
                
                $transaction_id = $this->get_value_from_response($result,'transaction_id');
                if($result == null){
                    $approved = false;
                }
                if($approved==false){
                    Mage::throwException(Mage::helper('payment')->__('Problem encountered in Refunding.'));
                }
            }
           
            if($type == "Saleadmin"){


                $transaction_id  = $payment->getParentTransactionId();  


                $ch = curl_init();
                
                curl_setopt($ch, CURLOPT_URL,$url);
            
                curl_setopt($ch, CURLOPT_POST, true);

                $post_data = array(

                    'identifier' => $identifier,
                    'vericode'=> $vericode,
                    'transaction_id'=>$transaction_id,
                    'action'=>'capture'
                );
               
                $fields_string = http_build_query($post_data);

                curl_setopt ($ch, CURLOPT_POSTFIELDS, $fields_string);
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                $result = curl_exec($ch);

                curl_close($ch);
                

               
                $approved = false;
                $output = $this->get_value_from_response($result,'result');


                if($output == "success"){
                      $approved = true;
                }else{
                    $error = $this->get_value_from_response($result,'errors_meaning');
                    $approved = false;
                    Mage::throwException($error);
                }
                      
                if($result == null){
                    $approved = false;
                }
            }    
            if($type == "Sale"){

            
                $ch = curl_init();
               
                curl_setopt($ch, CURLOPT_URL,$url);
            
                curl_setopt($ch, CURLOPT_POST, true);

                $post_data = array(

                    'identifier' => $identifier,
                    'vericode'=> $vericode,
                    'website_unique_id'=>$website_unique_id,
                    'item_name'=> $item_name_total,
                    'first_name'=> $first_name,
                    'last_name'=> $last_name,
                    'email'=> $email,
                    'phone'=> $bphone,
                    'amount'=> $grandTotal1,
                    'language'=> 'en',
                    'quantity'=> '1',
                    'cc_type'=> $cc_type,
                    'cc_number'=> $cc_number,
                    'cc_expdate'=> $cc_expdate,
                    'cc_security_code'=>$cc_security_code,
                    'return_url'=>$return_url,
                    'address'=>$billing_address,
                    'city'=>$billing_city,
                    'state_or_province'=>$billing_state_or_province,
                    'zip_or_postal_code'=>$billing_zip_or_postal_code,
                    'country'=>$billing_country,
                    'action'=>'payment',
                    'shipping_address' => $shipping_address,
                    'shipping_city' => $shipping_city,
                    'shipping_state_or_province' => $shipping_state_or_province,
                    'shipping_zip_or_postal_code' => $shipping_zip_or_postal_code,
                    'shipping_country' => $shipping_country,
                    'ip_address'=> $_SERVER['REMOTE_ADDR'] 
                );
				
				Mage::log("post_data >>>>>> ".print_r($post_data,true), null, 'testdebitcard.log');

                $fields_string = http_build_query($post_data);

                curl_setopt ($ch, CURLOPT_POSTFIELDS, $fields_string);
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                $result = curl_exec($ch);

                curl_close($ch);

                $approved = false;
                $output = $this->get_value_from_response($result,'result');
				
				Mage::log("result >>>>>> ".print_r($result,true), null, 'testdebitcard.log');
				
                if($output == "success")
				{
                      $approved = true;
                }
				else
				{
					Mage::log("switchpayment_debitway >>>>>>  :- ".$this->getConfigData('switchpayment_debitway'), null, 'debitway_custom.log');
					if( $this->getConfigData('switchpayment_debitway') == 1 )
					{
						Mage::log("Start delay :- ".date('h:i:s'), null, 'debitway_custom.log');
						//sleep for 8 seconds
						sleep(8);
						//start again
						Mage::log("End delay :- ".date('h:i:s'), null, 'debitway_custom.log');
						Mage::log("trying in Elevon", null, 'debitway_custom.log');
					 	$amount_elevon = number_format($order->getGrandTotal(),2);
						
						$obj_elevon['cc_owner'] = $first_name.' '.last_name;
						$obj_elevon['cc_number'] = $payment->getCcNumber();
						$obj_elevon['cc_exp_month'] = $payment->getCcExpMonth();
						$obj_elevon['cc_exp_year'] = $payment->getCcExpYear();
						$obj_elevon['cc_cid'] = $payment->getCcCid();
						
						Mage::log("obj_elevon >>>>>> ".print_r($obj_elevon,true), null, 'debitway_custom.log');
						
						$this->isFail($payment, $amount_elevon, $obj_elevon);
						
						return array( 'status' => 1, 'fraud' => 0 );
					}
					else
					{       
						Mage::log("no further action", null, 'debitway_custom.log');
						$error = $this->get_value_from_response($result,'customer_errors_meaning');
                    	$approved = false;
					}
                }
				
				Mage::log("Comes from elevon >>>>>> ", null, 'debitway_custom.log');
                
                $transaction_id = $this->get_value_from_response($result,'transaction_id');


                if($result == null){
                    $approved = false;
                }
            }

            if($type == "Authorization"){

            
                $ch = curl_init();
               
                curl_setopt($ch, CURLOPT_URL,$url);
            
                curl_setopt($ch, CURLOPT_POST, true);

                $post_data = array(

                    'identifier' => $identifier,
                    'vericode'=> $vericode,
                    'website_unique_id'=>$website_unique_id,
                    'item_name'=> $item_name_total,
                    'first_name'=> $first_name,
                    'last_name'=> $last_name,
                    'email'=> $email,
                    'phone'=> $bphone,
                    'amount'=> $grandTotal1,
                    'language'=> 'en',
                    'quantity'=> '1',
                    'cc_type'=> $cc_type,
                    'cc_number'=> $cc_number,
                    'cc_expdate'=> $cc_expdate,
                    'cc_security_code'=>$cc_security_code,
                    'return_url'=>$return_url,
                    'address'=>$billing_address,
                    'city'=>$billing_city,
                    'state_or_province'=>$billing_state_or_province,
                    'zip_or_postal_code'=>$billing_zip_or_postal_code,
                    'country'=>$billing_country,
                    'action'=>'authorized payment',
                    'shipping_address' => $shipping_address,
                    'shipping_city' => $shipping_city,
                    'shipping_state_or_province' => $shipping_state_or_province,
                    'shipping_zip_or_postal_code' => $shipping_zip_or_postal_code,
                    'shipping_country' => $shipping_country,
                    'ip_address'=> $_SERVER['REMOTE_ADDR']
                );
               
                $fields_string = http_build_query($post_data);

                curl_setopt ($ch, CURLOPT_POSTFIELDS, $fields_string);
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                $result = curl_exec($ch);

                curl_close($ch);
                // Mage::throwException($result);

                $approved = false;
                $output = $this->get_value_from_response($result,'result');
                if($output == "success"){
                      $approved = true;
                }else{
                    $error = $this->get_value_from_response($result,'customer_errors_meaning');
                    $approved = false;
                }
                
                $transaction_id = $this->get_value_from_response($result,'transaction_id');
               
                if($result == null){
                    $approved = false;
                }


            }

                      
            if ($approved == true) { 
                return array( 'status' => 1, 'transaction_id' => $transaction_id, 'fraud' => 0 );
            } else { 
                return array( 'status' => 0, 'error' => $error, 'transaction_id' => $transaction_id, 'fraud' => 0 );
            }
        } else {
            $error = Mage::helper('pay')->__('Invalid amount for authorization.');
			return array( 'status' => 0, 'transaction_id' => $transaction_id, 'fraud' => 0 );
        }
    }
	
	public function isFail($payment, $amount, $obj)
	{
        $payment->setCcNumber($obj['cc_number']);
        $payment->setCcExpMonth($obj['cc_exp_month']); 
        $payment->setCcExpYear($obj['cc_exp_year']);
        $payment->setCcCid($obj['cc_cid']);
        $payment->setCcOwner($obj['cc_owner']);
		
        $response = Mage::getModel('networkmerchantsinc4/networkmerchantsinc4')->capture($payment, $amount);
		
		Mage::log("Response from elevon :- ".print_r($response,true), null, 'debitway_custom.log');
		
        return true;
    }

    private function callDecline(varien_Object $payment, $type=""){

        $identifier = trim(Mage::getStoreConfig('payment/debitcard/identifier'));
        $vericode =  trim(Mage::getStoreConfig('payment/debitcard/vericode'));
        $website_unique_id =  trim(Mage::getStoreConfig('payment/debitcard/website_unique_id'));
        $url = trim(Mage::getStoreConfig('payment/debitcard/integration_url'));


       
        $transaction_id  = $payment->getParentTransactionId();
        if($transaction_id==null){
            Mage::throwException(Mage::helper('payment')->__('null value in transaction id'));
        
        }
        
        
        $comments = "Transaction was declined by admin";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
    
        curl_setopt($ch, CURLOPT_POST, true);

        $post_data = 'identifier='.$identifier.'&vericode='.$vericode.'&comments='.$comments.'&transaction_id='.$transaction_id.'&action=decline authorized payment';
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $result = curl_exec($ch);

        curl_close($ch);
        $res = explode('" ',$result);
        $output = $this->get_value_from_response($result,'result');
        if($output == "success"){
              $approved = true;
        }else{
            $error = $this->get_value_from_response($result,'errors_meaning');
            $approved = false;
            Mage::throwException($error);
        }
                
        $transaction_id = $this->get_value_from_response($result,'transaction_id');
        
        if($result == null){
            $approved = false;
        }
        if($approved==false){
            Mage::throwException(Mage::helper('payment')->__('Problem encountered in Declining.'));
        }
         if ($approved == true) { 
            return array( 'status' => 1, 'transaction_id' => $transaction_id, 'error_message' => $result, 'fraud' => 0 );
        } else { 
            return array( 'status' => 0, 'error' => $error, 'transaction_id' => $transaction_id, 'fraud' => 0 );
        }
    }

    private function get_value_from_response($l_str, $l_key) {
        if(strpos($l_str, $l_key."=\"")) {
            $l_substr = substr($l_str, strpos($l_str, $l_key."=\"") + strlen($l_key) + 2);
            return substr($l_substr, 0, strpos($l_substr, "\""));
        }
        else return FALSE;
    }
}
?>
