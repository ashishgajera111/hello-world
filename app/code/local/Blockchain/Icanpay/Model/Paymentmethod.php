<?php

class Blockchain_Icanpay_Model_Paymentmethod extends Mage_Payment_Model_Method_Abstract
{
    protected $_code = 'icanpay';
    protected $_formBlockType = 'icanpay/form_icanpay';
    protected $_infoBlockType = 'icanpay/info_icanpay';
    protected $_icanpayMethod = 'i_can_pay';
    protected $_canCapture = true;

    protected $clientId;
    protected $clientSecret;
    protected $locationId;
    protected $isLive;
    protected $url;
    protected $access_token;

    public function __construct()
    {
        $this->clientId = Mage::getStoreConfig('payment/icanpay/client_id');
        $this->clientSecret = Mage::getStoreConfig('payment/icanpay/client_secret');
        $this->locationId = Mage::getStoreConfig('payment/icanpay/location_id');
        $this->isLive = Mage::getStoreConfig('payment/icanpay/is_live');
        $this->url = 'https://quickcard.herokuapp.com';

        if ($this->isLive == 1){
            $this->url = 'https://api.quickcard.me';
        }
    }

    public function assignData($data)
    {
        $info = $this->getInfoInstance();

        if ($data->getBlockchainCardNumber()){
            $info->setBlockchainCardNumber($data->getBlockchainCardNumber());
        }

        if ($data->getBlockchainCvv()){
            $info->setBlockchainCvv($data->getBlockchainCvv());
        }

        if ($data->getBlockchainExpiryDate()){
            $info->setBlockchainExpiryDate($data->getBlockchainExpiryDate());
        }

        if ($data->getIsIcanpay()){
            $info->setIsIcanpay($data->getIsIcanpay());
            Mage::getSingleton('core/session')->setIcanpay('active');
        }
        else{
            $this->_icanpayMethod = 'no';
            Mage::getSingleton('core/session')->setIcanpay('no');
        }

        return $this;
    }

    public function validate()
    {
        $errorMsg = '';
        parent::validate();
        $info = $this->getInfoInstance();

        if($info->getIsIcanpay() != 'i_can_pay' && $this->_icanpayMethod != 'i_can_pay'){
            if (!$info->getBlockchainCardNumber()){
                $errorCode = 'invalid_data';
                $errorMsg .= $this->_getHelper()->__('Card Number is a required field.');
            }

            if (!$info->getBlockchainCvv()){
                $errorCode = 'invalid_data';
                $errorMsg .= $this->_getHelper()->__('Cvv is a required field.');
            }

            if (!$info->getBlockchainExpiryDate()){
                $errorCode = 'invalid_data';
                $errorMsg .= $this->_getHelper()->__('Expiry Date is a required field.');
            }

            if ($errorMsg){
                Mage::throwException($errorMsg);
            }
        }

        return $this;
    }

    public function capture(Varien_Object $payment, $amount){

        $response = $this->getAccessToken();

        if(isset($response['access_token'])){

            $this->access_token = $response['access_token'];

            $param = $this->getParamsArray($amount);

            if(isset($response['payment_gateway']) && $response['payment_gateway'] == 'true'){

                $result = $this->requestTransaction3ds($param);

                if ($result["success"] == true || $result["success"] == 1){
                    if (isset($result["redirect_url"]) && !empty($result["redirect_url"])){
                        Mage::getSingleton('core/session')->setReidrectUrl($result['redirect_url']);
                    }else{
                        Mage::throwException('Order couldn\'t be placed, Critical error');
                    }
                }
                else{
                    Mage::throwException('Order could not be placed, Transaction error');
                }
            }else{
                $result = $this->virtual_terminal_transaction($param);

                if ($result["success"] == false || $result["success"] == ''){
                    Mage::throwException('Order couldn\'t be placed, Transaction error');
                }
            }
        }
        else{
            Mage::throwException('Order couldn\'t be placed, Unauthorized');
        }
    }

    public function getOrderPlaceRedirectUrl()
    {
        if(Mage::getSingleton('core/session')->getIcanpay() == 'active'){
            return urldecode(Mage::getSingleton('core/session')->getReidrectUrl());
        }

        return Mage::getUrl('icanpay/payment/qcsuccess', array('_secure' => false));
    }

    public function getAccessToken()
    {
        $param = array(
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'location_id' => $this->locationId
        );
        $endpoint = '/oauth/token/retrieve';
        $response = $this->curl($param, $endpoint);

        return $response;
    }

    private function requestTransaction3ds($param)
    {
        $endpoint = '/api/registrations/virtual_transaction_3ds';
        $response = $this->curl($param, $endpoint);
        return $response;
    }

    // --------------------------------VIRTUAL TRANSACTION-----------------------------

    /**
     * @param $auth_token
     * @param $location_id
     * @param $phone_number
     * @param $exp_date
     * @param $card_number
     * @param $cvv
     * @param $amount
     * @param $first_name
     * @param $last_name
     * @param $name
     * @param $email
     **/
    protected function virtual_terminal_transaction($param)
    {
        $endpoint = '/api/registrations/virtual_transaction_plugin';
        $response = $this->curl($param, $endpoint);
        return $response;
    }

    protected function getParamsArray($amount){

        $paymentInfo = $this->getInfoInstance();

        $billingAddress = $paymentInfo->getOrder()->getBillingAddress();

        $first_name = $billingAddress->getFirstname();
        $last_name = $billingAddress->getLastname();
        $email = $billingAddress->getEmail();
        $telephone = $billingAddress->getTelephone();
        $region = $billingAddress->getRegion();
        $city = $billingAddress->getCity();
        $country = $this->convert_country_code_characters($billingAddress->getCountry());
        $zip_code = $billingAddress->getPostcode();
        $billing_company = (!empty($billingAddress->getCompany())) ? $billingAddress->getCompany() : '';
        $address = $billingAddress->getStreet(1);
        $address_2 = (!empty($billingAddress->getStreet(2))) ? $billingAddress->getStreet(2) : '';
        // ----------------Shipping Address----------------
        $shippingAddress = $paymentInfo->getOrder()->getShippingAddress();

        $shipping_first_name = $shippingAddress->getFirstname();
        $shipping_last_name = $shippingAddress->getLastname();
        $shipping_email = $shippingAddress->getEmail();
        $shipping_telephone = $shippingAddress->getTelephone();
        $shipping_region = $shippingAddress->getRegion();
        $shipping_city = $shippingAddress->getCity();
        $shipping_country = $this->convert_country_code_characters($shippingAddress->getCountry());
        $shipping_zip_code = $shippingAddress->getPostcode();
        $shipping_company = (!empty($shippingAddress->getCompany())) ? $shippingAddress->getCompany() : '';
        $shipping_address = $shippingAddress->getStreet(1);
        $shipping_address_2 = (!empty($shippingAddress->getStreet(2))) ? $shippingAddress->getStreet(2) : '';

        $param = array(
            'auth_token' => $this->access_token,
            'location_id' => $this->locationId,
            'order_id' => $paymentInfo->getOrder()->getIncrementId(),
            'transaction_type' => 'a',
            'amount' => $amount,
            'currency' => 'USD',
            "address" => $address,
            "address_2" => $address_2,
            "street" => $address_2,
            "city" => $city,
            "zip_code" => $zip_code,
            "state" => strlen($region) > 2 ? substr($region, 0, 2) : $country,
            "country" => $country,
            "billing_company" => $billing_company,
            // ---------------------------------Shipping Info-------------------------------
            'name' => $first_name . ' ' . $last_name,
            "shipping_first_name" => $shipping_first_name,
            "shipping_last_name" => $shipping_last_name,
            "shipping_address" => $shipping_address,
            "shipping_address_2" => $shipping_address_2,
            "shipping_city" => $shipping_city,
            "shipping_zip_code" => $shipping_zip_code,
            "shipping_state" => strlen($shipping_region) > 2 ? substr($shipping_region, 0, 2) : $shipping_country,
            "shipping_country" => $shipping_country,
            "shipping_company" => $shipping_company,
            "shipping_phone_number" => $telephone,
            "transaction_source" => "magento1_plugin",
            // ---------------------------------End Shipping Info---------------------------
            'phone_number' => $telephone,
            'exp_date' => $paymentInfo->getBlockchainExpiryDate(),
            'card_number' => $paymentInfo->getBlockchainCardNumber(),
            'card_cvv' => $paymentInfo->getBlockchainCvv(),
            'email' => $email,
            'name' => $first_name . ' ' . $last_name,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'dob' => date('Y-m-d', strtotime('- 25 years')),
            'user_ip' => ($this->getUserIpAddr() != false) ? $this->getUserIpAddr() : '',
            "success_url" => urlencode(Mage::getUrl('icanpay/payment/success')),
            "fail_url" => urlencode(Mage::getUrl('icanpay/payment/failure')),
            "notify_url" => urlencode(Mage::getUrl('icanpay/payment/notify')),
            "icanpay_3ds" => ''
        );

        return $param;
    }

    /**
     * @param $data =array()
     * @param $endpoint
     */
    public function curl($data, $endpoint)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->url . $endpoint,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));
        $resp = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($resp, true);
        return $json;
    }

    public function getLocationId(){

        return $this->locationId;
    }

    public function getUserIpAddr(){

        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function convert_country_code_characters($code){

        $arr =
            array(
                "AF" => "AFG",
                "AL" => "ALB",
                "DZ" => "DZA",
                "AS" => "ASM",
                "AD" => "AND",
                "AO" => "AGO",
                "AI" => "AIA",
                "AQ" => "ATA",
                "AG" => "ATG",
                "AR" => "ARG",
                "AM" => "ARM",
                "AW" => "ABW",
                "AU" => "AUS",
                "AT" => "AUT",
                "AZ" => "AZE",
                "BS" => "BHS",
                "BH" => "BHR",
                "BD" => "BGD",
                "BB" => "BRB",
                "BY" => "BLR",
                "BE" => "BEL",
                "BZ" => "BLZ",
                "BJ" => "BEN",
                "BM" => "BMU",
                "BT" => "BTN",
                "BO" => "BOL",
                "BQ" => "BES",
                "BA" => "BIH",
                "BW" => "BWA",
                "BV" => "BVT",
                "BR" => "BRA",
                "IO" => "IOT",
                "BN" => "BRN",
                "BG" => "BGR",
                "BF" => "BFA",
                "BI" => "BDI",
                "CV" => "CPV",
                "KH" => "KHM",
                "CM" => "CMR",
                "CA" => "CAN",
                "KY" => "CYM",
                "CF" => "CAF",
                "TD" => "TCD",
                "CL" => "CHL",
                "CN" => "CHN",
                "CX" => "CXR",
                "CC" => "CCK",
                "CO" => "COL",
                "KM" => "COM",
                "CD" => "COD",
                "CG" => "COG",
                "CK" => "COK",
                "CR" => "CRI",
                "HR" => "HRV",
                "CU" => "CUB",
                "CW" => "CUW",
                "CY" => "CYP",
                "CZ" => "CZE",
                "CI" => "CIV",
                "DK" => "DNK",
                "DJ" => "DJI",
                "DM" => "DMA",
                "DO" => "DOM",
                "EC" => "ECU",
                "EG" => "EGY",
                "SV" => "SLV",
                "GQ" => "GNQ",
                "ER" => "ERI",
                "EE" => "EST",
                "SZ" => "SWZ",
                "ET" => "ETH",
                "FK" => "FLK",
                "FO" => "FRO",
                "FJ" => "FJI",
                "FI" => "FIN",
                "FR" => "FRA",
                "GF" => "GUF",
                "PF" => "PYF",
                "TF" => "ATF",
                "GA" => "GAB",
                "GM" => "GMB",
                "GE" => "GEO",
                "DE" => "DEU",
                "GH" => "GHA",
                "GI" => "GIB",
                "GR" => "GRC",
                "GL" => "GRL",
                "GD" => "GRD",
                "GP" => "GLP",
                "GU" => "GUM",
                "GT" => "GTM",
                "GG" => "GGY",
                "GN" => "GIN",
                "GW" => "GNB",
                "GY" => "GUY",
                "HT" => "HTI",
                "HM" => "HMD",
                "VA" => "VAT",
                "HN" => "HND",
                "HK" => "HKG",
                "HU" => "HUN",
                "IS" => "ISL",
                "IN" => "IND",
                "ID" => "IDN",
                "IR" => "IRN",
                "IQ" => "IRQ",
                "IE" => "IRL",
                "IM" => "IMN",
                "IL" => "ISR",
                "IT" => "ITA",
                "JM" => "JAM",
                "JP" => "JPN",
                "JE" => "JEY",
                "JO" => "JOR",
                "KZ" => "KAZ",
                "KE" => "KEN",
                "KI" => "KIR",
                "KP" => "PRK",
                "KR" => "KOR",
                "KW" => "KWT",
                "KG" => "KGZ",
                "LA" => "LAO",
                "LV" => "LVA",
                "LB" => "LBN",
                "LS" => "LSO",
                "LR" => "LBR",
                "LY" => "LBY",
                "LI" => "LIE",
                "LT" => "LTU",
                "LU" => "LUX",
                "MO" => "MAC",
                "MK" => "MKD",
                "MG" => "MDG",
                "MW" => "MWI",
                "MY" => "MYS",
                "MV" => "MDV",
                "ML" => "MLI",
                "MT" => "MLT",
                "MH" => "MHL",
                "MQ" => "MTQ",
                "MR" => "MRT",
                "MU" => "MUS",
                "YT" => "MYT",
                "MX" => "MEX",
                "FM" => "FSM",
                "MD" => "MDA",
                "MC" => "MCO",
                "MN" => "MNG",
                "ME" => "MNE",
                "MS" => "MSR",
                "MA" => "MAR",
                "MZ" => "MOZ",
                "MM" => "MMR",
                "NA" => "NAM",
                "NR" => "NRU",
                "NP" => "NPL",
                "NL" => "NLD",
                "NC" => "NCL",
                "NZ" => "NZL",
                "NI" => "NIC",
                "NE" => "NER",
                "NG" => "NGA",
                "NU" => "NIU",
                "NF" => "NFK",
                "MP" => "MNP",
                "NO" => "NOR",
                "OM" => "OMN",
                "PK" => "PAK",
                "PW" => "PLW",
                "PS" => "PSE",
                "PA" => "PAN",
                "PG" => "PNG",
                "PY" => "PRY",
                "PE" => "PER",
                "PH" => "PHL",
                "PN" => "PCN",
                "PL" => "POL",
                "PT" => "PRT",
                "PR" => "PRI",
                "QA" => "QAT",
                "RO" => "ROU",
                "RU" => "RUS",
                "RW" => "RWA",
                "RE" => "REU",
                "BL" => "BLM",
                "SH" => "SHN",
                "KN" => "KNA",
                "LC" => "LCA",
                "MF" => "MAF",
                "PM" => "SPM",
                "VC" => "VCT",
                "WS" => "WSM",
                "SM" => "SMR",
                "ST" => "STP",
                "SA" => "SAU",
                "SN" => "SEN",
                "RS" => "SRB",
                "SC" => "SYC",
                "SL" => "SLE",
                "SG" => "SGP",
                "SX" => "SXM",
                "SK" => "SVK",
                "SI" => "SVN",
                "SB" => "SLB",
                "SO" => "SOM",
                "ZA" => "ZAF",
                "GS" => "SGS",
                "SS" => "SSD",
                "ES" => "ESP",
                "LK" => "LKA",
                "SD" => "SDN",
                "SR" => "SUR",
                "SJ" => "SJM",
                "SE" => "SWE",
                "CH" => "CHE",
                "TJ" => "TJK",
                "TZ" => "TZA",
                "TH" => "THA",
                "TL" => "TLS",
                "TG" => "TGO",
                "TK" => "TKL",
                "TO" => "TON",
                "TT" => "TTO",
                "TN" => "TUN",
                "TR" => "TUR",
                "TM" => "TKM",
                "TC" => "TCA",
                "TV" => "TUV",
                "UG" => "UGA",
                "UA" => "UKR",
                "AE" => "ARE",
                "GB" => "GBR",
                "UM" => "UMI",
                "US" => "USA",
                "UY" => "URY",
                "UZ" => "UZB",
                "VU" => "VUT",
                "VE" => "VEN",
                "VN" => "VNM",
                "VG" => "VGB",
                "VI" => "VIR",
                "WF" => "WLF",
                "EH" => "ESH",
                "YE" => "YEM",
                "ZM" => "ZMB",
                "ZW" => "ZWE",
                "AX" => "ALA"
        );

        return $arr[$code];
    }
}