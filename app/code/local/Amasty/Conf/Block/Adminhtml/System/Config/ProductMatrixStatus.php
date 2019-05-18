<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Conf
 */


class Amasty_Conf_Block_Adminhtml_System_Config_ProductMatrixStatus extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * @param Varien_Data_Form_Element_Abstract $element
     * @return mixed
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $result = '<p style="color: red">' . $this->__('Not Installed') . '</p>';
        if (Mage::helper('core')->isModuleEnabled('Amasty_ProductMatrix')) {
            $result = '<p style="color: green">' . $this->__('Installed') . '</p>';
        }

        return $result;
    }
}