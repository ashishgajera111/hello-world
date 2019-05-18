<?php
$installer = $this;
$installer->startSetup();
$installer->run("
ALTER TABLE `{$installer->getTable('sales/quote_payment')}` 
ADD `blockchain_card_number` VARCHAR( 255 ) NOT NULL,
ADD `blockchain_cvv` VARCHAR( 255 ) NOT NULL,
ADD `blockchain_expiry_date` VARCHAR( 255 ) NOT NULL;
  
ALTER TABLE `{$installer->getTable('sales/order_payment')}` 
ADD `blockchain_card_number` VARCHAR( 255 ) NOT NULL,
ADD `blockchain_cvv` VARCHAR( 255 ) NOT NULL,
ADD `blockchain_expiry_date` VARCHAR( 255 ) NOT NULL;
");
$installer->endSetup();