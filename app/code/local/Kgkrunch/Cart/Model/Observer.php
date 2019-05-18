<?php
/**
 * @category   MagePsycho
 * @package    MagePsycho_Productsort
 * @author     magepsycho@gmail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Kgkrunch_Cart_Model_Observer
{
 
    public function coupon(Varien_Event_Observer $observer)
    {
		$cart = Mage::getSingleton('checkout/cart');
		$cart->init();
		//$options = array('92'=>'49','144'=>'21');
		$product = Mage::getModel('catalog/product')->load(2);
		
		$paramater = array('product' => '2',
							'qty' => '1',
							'form_key' => Mage::getSingleton('core/session')->getFormKey()
					);       
		
		$request = new Varien_Object();
		$request->setData($paramater);
		$cart->addProduct($product, $request);
		$cart->save();
    }
}