-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 12, 2019 at 09:55 PM
-- Server version: 10.2.26-MariaDB-log
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `matt3161_live_m2`
--

-- --------------------------------------------------------

--
-- Table structure for table `eav_attribute`
--

CREATE TABLE `eav_attribute` (
  `attribute_id` smallint(5) UNSIGNED NOT NULL COMMENT 'Attribute Id',
  `entity_type_id` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Entity Type Id',
  `attribute_code` varchar(255) NOT NULL COMMENT 'Attribute Code',
  `attribute_model` varchar(255) DEFAULT NULL COMMENT 'Attribute Model',
  `backend_model` varchar(255) DEFAULT NULL COMMENT 'Backend Model',
  `backend_type` varchar(8) NOT NULL DEFAULT 'static' COMMENT 'Backend Type',
  `backend_table` varchar(255) DEFAULT NULL COMMENT 'Backend Table',
  `frontend_model` varchar(255) DEFAULT NULL COMMENT 'Frontend Model',
  `frontend_input` varchar(50) DEFAULT NULL COMMENT 'Frontend Input',
  `frontend_label` varchar(255) DEFAULT NULL COMMENT 'Frontend Label',
  `frontend_class` varchar(255) DEFAULT NULL COMMENT 'Frontend Class',
  `source_model` varchar(255) DEFAULT NULL COMMENT 'Source Model',
  `is_required` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Defines Is Required',
  `is_user_defined` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Defines Is User Defined',
  `default_value` text DEFAULT NULL COMMENT 'Default Value',
  `is_unique` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Defines Is Unique',
  `note` varchar(255) DEFAULT NULL COMMENT 'Note'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Eav Attribute';

--
-- Dumping data for table `eav_attribute`
--

INSERT INTO `eav_attribute` (`attribute_id`, `entity_type_id`, `attribute_code`, `attribute_model`, `backend_model`, `backend_type`, `backend_table`, `frontend_model`, `frontend_input`, `frontend_label`, `frontend_class`, `source_model`, `is_required`, `is_user_defined`, `default_value`, `is_unique`, `note`) VALUES
(1, 1, 'website_id', NULL, 'Magento\\Customer\\Model\\Customer\\Attribute\\Backend\\Website', 'static', NULL, NULL, 'select', 'Associate to Website', NULL, 'Magento\\Customer\\Model\\Customer\\Attribute\\Source\\Website', 1, 0, NULL, 0, NULL),
(2, 1, 'store_id', NULL, 'Magento\\Customer\\Model\\Customer\\Attribute\\Backend\\Store', 'static', NULL, NULL, 'select', 'Create In', NULL, 'Magento\\Customer\\Model\\Customer\\Attribute\\Source\\Store', 1, 0, NULL, 0, NULL),
(3, 1, 'created_in', NULL, NULL, 'static', NULL, NULL, 'text', 'Created From', NULL, NULL, 0, 0, NULL, 0, NULL),
(4, 1, 'prefix', NULL, NULL, 'static', NULL, NULL, 'text', 'Name Prefix', NULL, NULL, 0, 0, NULL, 0, NULL),
(5, 1, 'firstname', NULL, NULL, 'static', NULL, NULL, 'text', 'First Name', NULL, NULL, 1, 0, NULL, 0, NULL),
(6, 1, 'middlename', NULL, NULL, 'static', NULL, NULL, 'text', 'Middle Name/Initial', NULL, NULL, 0, 0, NULL, 0, NULL),
(7, 1, 'lastname', NULL, NULL, 'static', NULL, NULL, 'text', 'Last Name', NULL, NULL, 1, 0, NULL, 0, NULL),
(8, 1, 'suffix', NULL, NULL, 'static', NULL, NULL, 'text', 'Name Suffix', NULL, NULL, 0, 0, NULL, 0, NULL),
(9, 1, 'email', NULL, NULL, 'static', NULL, NULL, 'text', 'Email', NULL, NULL, 1, 0, NULL, 0, NULL),
(10, 1, 'group_id', NULL, NULL, 'static', NULL, NULL, 'select', 'Group', NULL, 'Magento\\Customer\\Model\\Customer\\Attribute\\Source\\Group', 1, 0, NULL, 0, NULL),
(11, 1, 'dob', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Backend\\Datetime', 'static', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Frontend\\Datetime', 'date', 'Date of Birth', NULL, NULL, 0, 0, NULL, 0, NULL),
(12, 1, 'password_hash', NULL, 'Magento\\Customer\\Model\\Customer\\Attribute\\Backend\\Password', 'static', NULL, NULL, 'hidden', NULL, NULL, NULL, 0, 0, NULL, 0, NULL),
(13, 1, 'rp_token', NULL, NULL, 'static', NULL, NULL, 'hidden', NULL, NULL, NULL, 0, 0, NULL, 0, NULL),
(14, 1, 'rp_token_created_at', NULL, NULL, 'static', NULL, NULL, 'date', NULL, NULL, NULL, 0, 0, NULL, 0, NULL),
(15, 1, 'default_billing', NULL, 'Magento\\Customer\\Model\\Customer\\Attribute\\Backend\\Billing', 'static', NULL, NULL, 'text', 'Default Billing Address', NULL, NULL, 0, 0, NULL, 0, NULL),
(16, 1, 'default_shipping', NULL, 'Magento\\Customer\\Model\\Customer\\Attribute\\Backend\\Shipping', 'static', NULL, NULL, 'text', 'Default Shipping Address', NULL, NULL, 0, 0, NULL, 0, NULL),
(17, 1, 'taxvat', NULL, NULL, 'static', NULL, NULL, 'text', 'Tax/VAT Number', NULL, NULL, 0, 0, NULL, 0, NULL),
(18, 1, 'confirmation', NULL, NULL, 'static', NULL, NULL, 'text', 'Is Confirmed', NULL, NULL, 0, 0, NULL, 0, NULL),
(19, 1, 'created_at', NULL, NULL, 'static', NULL, NULL, 'date', 'Created At', NULL, NULL, 0, 0, NULL, 0, NULL),
(20, 1, 'gender', NULL, NULL, 'static', NULL, NULL, 'select', 'Gender', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Table', 0, 0, NULL, 0, NULL),
(21, 1, 'disable_auto_group_change', NULL, 'Magento\\Customer\\Model\\Attribute\\Backend\\Data\\Boolean', 'static', NULL, NULL, 'boolean', 'Disable Automatic Group Change Based on VAT ID', NULL, NULL, 0, 0, '0', 0, NULL),
(22, 2, 'prefix', NULL, NULL, 'static', NULL, NULL, 'text', 'Name Prefix', NULL, NULL, 0, 0, NULL, 0, NULL),
(23, 2, 'firstname', NULL, NULL, 'static', NULL, NULL, 'text', 'First Name', NULL, NULL, 1, 0, NULL, 0, NULL),
(24, 2, 'middlename', NULL, NULL, 'static', NULL, NULL, 'text', 'Middle Name/Initial', NULL, NULL, 0, 0, NULL, 0, NULL),
(25, 2, 'lastname', NULL, NULL, 'static', NULL, NULL, 'text', 'Last Name', NULL, NULL, 1, 0, NULL, 0, NULL),
(26, 2, 'suffix', NULL, NULL, 'static', NULL, NULL, 'text', 'Name Suffix', NULL, NULL, 0, 0, NULL, 0, NULL),
(27, 2, 'company', NULL, NULL, 'static', NULL, NULL, 'text', 'Company', NULL, NULL, 0, 0, NULL, 0, NULL),
(28, 2, 'street', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Backend\\DefaultBackend', 'static', NULL, NULL, 'multiline', 'Street Address', NULL, NULL, 1, 0, NULL, 0, NULL),
(29, 2, 'city', NULL, NULL, 'static', NULL, NULL, 'text', 'City', NULL, NULL, 1, 0, NULL, 0, NULL),
(30, 2, 'country_id', NULL, NULL, 'static', NULL, NULL, 'select', 'Country', NULL, 'Magento\\Customer\\Model\\ResourceModel\\Address\\Attribute\\Source\\Country', 1, 0, NULL, 0, NULL),
(31, 2, 'region', NULL, 'Magento\\Customer\\Model\\ResourceModel\\Address\\Attribute\\Backend\\Region', 'static', NULL, NULL, 'text', 'State/Province', NULL, NULL, 0, 0, NULL, 0, NULL),
(32, 2, 'region_id', NULL, NULL, 'static', NULL, NULL, 'hidden', 'State/Province', NULL, 'Magento\\Customer\\Model\\ResourceModel\\Address\\Attribute\\Source\\Region', 0, 0, NULL, 0, NULL),
(33, 2, 'postcode', NULL, NULL, 'static', NULL, NULL, 'text', 'Zip/Postal Code', NULL, NULL, 0, 0, NULL, 0, NULL),
(34, 2, 'telephone', NULL, NULL, 'static', NULL, NULL, 'text', 'Phone Number', NULL, NULL, 1, 0, NULL, 0, NULL),
(35, 2, 'fax', NULL, NULL, 'static', NULL, NULL, 'text', 'Fax', NULL, NULL, 0, 0, NULL, 0, NULL),
(36, 2, 'vat_id', NULL, NULL, 'static', NULL, NULL, 'text', 'VAT Number', NULL, NULL, 0, 0, NULL, 0, NULL),
(37, 2, 'vat_is_valid', NULL, NULL, 'static', NULL, NULL, 'text', 'VAT number validity', NULL, NULL, 0, 0, NULL, 0, NULL),
(38, 2, 'vat_request_id', NULL, NULL, 'static', NULL, NULL, 'text', 'VAT number validation request ID', NULL, NULL, 0, 0, NULL, 0, NULL),
(39, 2, 'vat_request_date', NULL, NULL, 'static', NULL, NULL, 'text', 'VAT number validation request date', NULL, NULL, 0, 0, NULL, 0, NULL),
(40, 2, 'vat_request_success', NULL, NULL, 'static', NULL, NULL, 'text', 'VAT number validation request success', NULL, NULL, 0, 0, NULL, 0, NULL),
(41, 1, 'updated_at', NULL, NULL, 'static', NULL, NULL, 'date', 'Updated At', NULL, NULL, 0, 0, NULL, 0, NULL),
(42, 1, 'failures_num', NULL, NULL, 'static', NULL, NULL, 'hidden', 'Failures Number', NULL, NULL, 0, 0, NULL, 0, NULL),
(43, 1, 'first_failure', NULL, NULL, 'static', NULL, NULL, 'date', 'First Failure Date', NULL, NULL, 0, 0, NULL, 0, NULL),
(44, 1, 'lock_expires', NULL, NULL, 'static', NULL, NULL, 'date', 'Failures Number', NULL, NULL, 0, 0, NULL, 0, NULL),
(45, 3, 'name', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Name', NULL, NULL, 1, 0, NULL, 0, NULL),
(46, 3, 'is_active', NULL, NULL, 'int', NULL, NULL, 'select', 'Is Active', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Boolean', 1, 0, NULL, 0, NULL),
(47, 3, 'description', NULL, NULL, 'text', NULL, NULL, 'textarea', 'Description', NULL, NULL, 0, 0, NULL, 0, NULL),
(48, 3, 'image', NULL, 'Magento\\Catalog\\Model\\Category\\Attribute\\Backend\\Image', 'varchar', NULL, NULL, 'image', 'Image', NULL, NULL, 0, 0, NULL, 0, NULL),
(49, 3, 'meta_title', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Page Title', NULL, NULL, 0, 0, NULL, 0, NULL),
(50, 3, 'meta_keywords', NULL, NULL, 'text', NULL, NULL, 'textarea', 'Meta Keywords', NULL, NULL, 0, 0, NULL, 0, NULL),
(51, 3, 'meta_description', NULL, NULL, 'text', NULL, NULL, 'textarea', 'Meta Description', NULL, NULL, 0, 0, NULL, 0, NULL),
(52, 3, 'display_mode', NULL, NULL, 'varchar', NULL, NULL, 'select', 'Display Mode', NULL, 'Magento\\Catalog\\Model\\Category\\Attribute\\Source\\Mode', 0, 0, NULL, 0, NULL),
(53, 3, 'landing_page', NULL, NULL, 'int', NULL, NULL, 'select', 'CMS Block', NULL, 'Magento\\Catalog\\Model\\Category\\Attribute\\Source\\Page', 0, 0, NULL, 0, NULL),
(54, 3, 'is_anchor', NULL, NULL, 'int', NULL, NULL, 'hidden', 'Is Anchor', NULL, NULL, 0, 0, '1', 0, NULL),
(55, 3, 'path', NULL, NULL, 'static', NULL, NULL, 'text', 'Path', NULL, NULL, 0, 0, NULL, 0, NULL),
(56, 3, 'position', NULL, NULL, 'static', NULL, NULL, 'text', 'Position', NULL, NULL, 0, 0, NULL, 0, NULL),
(57, 3, 'all_children', NULL, NULL, 'text', NULL, NULL, 'text', NULL, NULL, NULL, 0, 0, NULL, 0, NULL),
(58, 3, 'path_in_store', NULL, NULL, 'text', NULL, NULL, 'text', NULL, NULL, NULL, 0, 0, NULL, 0, NULL),
(59, 3, 'children', NULL, NULL, 'text', NULL, NULL, 'text', NULL, NULL, NULL, 0, 0, NULL, 0, NULL),
(60, 3, 'custom_design', NULL, NULL, 'varchar', NULL, NULL, 'select', 'Custom Design', NULL, 'Magento\\Theme\\Model\\Theme\\Source\\Theme', 0, 0, NULL, 0, NULL),
(61, 3, 'custom_design_from', 'Magento\\Catalog\\Model\\ResourceModel\\Eav\\Attribute', 'Magento\\Catalog\\Model\\Attribute\\Backend\\Startdate', 'datetime', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Frontend\\Datetime', 'date', 'Active From', NULL, NULL, 0, 0, NULL, 0, NULL),
(62, 3, 'custom_design_to', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Backend\\Datetime', 'datetime', NULL, NULL, 'date', 'Active To', NULL, NULL, 0, 0, NULL, 0, NULL),
(63, 3, 'page_layout', NULL, NULL, 'varchar', NULL, NULL, 'select', 'Page Layout', NULL, 'Magento\\Catalog\\Model\\Category\\Attribute\\Source\\Layout', 0, 0, NULL, 0, NULL),
(64, 3, 'custom_layout_update', NULL, 'Magento\\Catalog\\Model\\Attribute\\Backend\\Customlayoutupdate', 'text', NULL, NULL, 'textarea', 'Custom Layout Update', NULL, NULL, 0, 0, NULL, 0, NULL),
(65, 3, 'level', NULL, NULL, 'static', NULL, NULL, 'text', 'Level', NULL, NULL, 0, 0, NULL, 0, NULL),
(66, 3, 'children_count', NULL, NULL, 'static', NULL, NULL, 'text', 'Children Count', NULL, NULL, 0, 0, NULL, 0, NULL),
(67, 3, 'available_sort_by', NULL, 'Magento\\Catalog\\Model\\Category\\Attribute\\Backend\\Sortby', 'text', NULL, NULL, 'multiselect', 'Available Product Listing Sort By', NULL, 'Magento\\Catalog\\Model\\Category\\Attribute\\Source\\Sortby', 1, 0, NULL, 0, NULL),
(68, 3, 'default_sort_by', NULL, 'Magento\\Catalog\\Model\\Category\\Attribute\\Backend\\Sortby', 'varchar', NULL, NULL, 'select', 'Default Product Listing Sort By', NULL, 'Magento\\Catalog\\Model\\Category\\Attribute\\Source\\Sortby', 1, 0, NULL, 0, NULL),
(69, 3, 'include_in_menu', NULL, NULL, 'int', NULL, NULL, 'select', 'Include in Navigation Menu', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Boolean', 1, 0, '1', 0, NULL),
(70, 3, 'custom_use_parent_settings', NULL, NULL, 'int', NULL, NULL, 'select', 'Use Parent Category Settings', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Boolean', 0, 0, NULL, 0, NULL),
(71, 3, 'custom_apply_to_products', NULL, NULL, 'int', NULL, NULL, 'select', 'Apply To Products', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Boolean', 0, 0, NULL, 0, NULL),
(72, 3, 'filter_price_range', NULL, NULL, 'decimal', NULL, NULL, 'text', 'Layered Navigation Price Step', NULL, NULL, 0, 0, NULL, 0, NULL),
(73, 4, 'name', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Product Name', 'validate-length maximum-length-255', NULL, 1, 0, NULL, 0, NULL),
(74, 4, 'sku', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Backend\\Sku', 'static', NULL, NULL, 'text', 'SKU', 'validate-length maximum-length-64', NULL, 1, 0, NULL, 1, NULL),
(75, 4, 'description', NULL, NULL, 'text', NULL, NULL, 'textarea', 'Description', NULL, NULL, 0, 0, NULL, 0, NULL),
(76, 4, 'short_description', NULL, NULL, 'text', NULL, NULL, 'textarea', 'Short Description', NULL, NULL, 0, 0, NULL, 0, NULL),
(77, 4, 'price', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Backend\\Price', 'decimal', NULL, NULL, 'price', 'Price', NULL, NULL, 1, 0, NULL, 0, NULL),
(78, 4, 'special_price', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Backend\\Price', 'decimal', NULL, NULL, 'price', 'Special Price', NULL, NULL, 0, 0, NULL, 0, NULL),
(79, 4, 'special_from_date', NULL, 'Magento\\Catalog\\Model\\Attribute\\Backend\\Startdate', 'datetime', NULL, NULL, 'date', 'Special Price From Date', NULL, NULL, 0, 0, NULL, 0, NULL),
(80, 4, 'special_to_date', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Backend\\Datetime', 'datetime', NULL, NULL, 'date', 'Special Price To Date', NULL, NULL, 0, 0, NULL, 0, NULL),
(81, 4, 'cost', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Backend\\Price', 'decimal', NULL, NULL, 'price', 'Cost', NULL, NULL, 0, 1, NULL, 0, NULL),
(82, 4, 'weight', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Backend\\Weight', 'decimal', NULL, NULL, 'weight', 'Weight', NULL, NULL, 0, 0, NULL, 0, NULL),
(83, 4, 'manufacturer', NULL, NULL, 'int', NULL, NULL, 'select', 'Brand', NULL, NULL, 0, 1, '', 0, NULL),
(84, 4, 'meta_title', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Meta Title', NULL, NULL, 0, 0, NULL, 0, NULL),
(85, 4, 'meta_keyword', NULL, NULL, 'text', NULL, NULL, 'textarea', 'Meta Keywords', NULL, NULL, 0, 0, NULL, 0, NULL),
(86, 4, 'meta_description', NULL, NULL, 'varchar', NULL, NULL, 'textarea', 'Meta Description', NULL, NULL, 0, 0, NULL, 0, 'Maximum 255 chars. Meta Description should optimally be between 150-160 characters'),
(87, 4, 'image', NULL, NULL, 'varchar', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Frontend\\Image', 'media_image', 'Base', NULL, NULL, 0, 0, NULL, 0, NULL),
(88, 4, 'small_image', NULL, NULL, 'varchar', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Frontend\\Image', 'media_image', 'Small', NULL, NULL, 0, 0, NULL, 0, NULL),
(89, 4, 'thumbnail', NULL, NULL, 'varchar', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Frontend\\Image', 'media_image', 'Thumbnail', NULL, NULL, 0, 0, NULL, 0, NULL),
(90, 4, 'media_gallery', NULL, NULL, 'static', NULL, NULL, 'gallery', 'Media Gallery', NULL, NULL, 0, 0, NULL, 0, NULL),
(91, 4, 'old_id', NULL, NULL, 'int', NULL, NULL, 'text', NULL, NULL, NULL, 0, 0, NULL, 0, NULL),
(92, 4, 'tier_price', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Backend\\Tierprice', 'decimal', NULL, NULL, 'text', 'Tier Price', NULL, NULL, 0, 0, NULL, 0, NULL),
(93, 4, 'color', NULL, NULL, 'int', NULL, NULL, 'select', 'Color', NULL, NULL, 0, 1, '46', 0, NULL),
(94, 4, 'news_from_date', NULL, 'Magento\\Catalog\\Model\\Attribute\\Backend\\Startdate', 'datetime', NULL, NULL, 'date', 'Set Product as New from Date', NULL, NULL, 0, 0, NULL, 0, NULL),
(95, 4, 'news_to_date', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Backend\\Datetime', 'datetime', NULL, NULL, 'date', 'Set Product as New to Date', NULL, NULL, 0, 0, NULL, 0, NULL),
(96, 4, 'gallery', NULL, NULL, 'varchar', NULL, NULL, 'gallery', 'Image Gallery', NULL, NULL, 0, 0, NULL, 0, NULL),
(97, 4, 'status', NULL, NULL, 'int', NULL, NULL, 'select', 'Enable Product', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Source\\Status', 0, 0, '1', 0, NULL),
(98, 4, 'minimal_price', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Backend\\Price', 'decimal', NULL, NULL, 'price', 'Minimal Price', NULL, NULL, 0, 0, NULL, 0, NULL),
(99, 4, 'visibility', NULL, NULL, 'int', NULL, NULL, 'select', 'Visibility', NULL, 'Magento\\Catalog\\Model\\Product\\Visibility', 0, 0, '4', 0, NULL),
(100, 4, 'custom_design', NULL, NULL, 'varchar', NULL, NULL, 'select', 'New Theme', NULL, 'Magento\\Theme\\Model\\Theme\\Source\\Theme', 0, 0, NULL, 0, NULL),
(101, 4, 'custom_design_from', NULL, 'Magento\\Catalog\\Model\\Attribute\\Backend\\Startdate', 'datetime', NULL, NULL, 'date', 'Active From', NULL, NULL, 0, 0, NULL, 0, NULL),
(102, 4, 'custom_design_to', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Backend\\Datetime', 'datetime', NULL, NULL, 'date', 'Active To', NULL, NULL, 0, 0, NULL, 0, NULL),
(103, 4, 'custom_layout_update', NULL, 'Magento\\Catalog\\Model\\Attribute\\Backend\\Customlayoutupdate', 'text', NULL, NULL, 'textarea', 'Layout Update XML', NULL, NULL, 0, 0, NULL, 0, NULL),
(104, 4, 'page_layout', NULL, NULL, 'varchar', NULL, NULL, 'select', 'Layout', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Source\\Layout', 0, 0, NULL, 0, NULL),
(105, 4, 'category_ids', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Backend\\Category', 'static', NULL, NULL, 'text', 'Categories', NULL, NULL, 0, 0, NULL, 0, NULL),
(106, 4, 'options_container', NULL, NULL, 'varchar', NULL, NULL, 'select', 'Display Product Options In', NULL, 'Magento\\Catalog\\Model\\Entity\\Product\\Attribute\\Design\\Options\\Container', 0, 0, 'container2', 0, NULL),
(107, 4, 'required_options', NULL, NULL, 'static', NULL, NULL, 'text', NULL, NULL, NULL, 0, 0, NULL, 0, NULL),
(108, 4, 'has_options', NULL, NULL, 'static', NULL, NULL, 'text', NULL, NULL, NULL, 0, 0, NULL, 0, NULL),
(109, 4, 'image_label', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Image Label', NULL, NULL, 0, 0, NULL, 0, NULL),
(110, 4, 'small_image_label', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Small Image Label', NULL, NULL, 0, 0, NULL, 0, NULL),
(111, 4, 'thumbnail_label', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Thumbnail Label', NULL, NULL, 0, 0, NULL, 0, NULL),
(112, 4, 'created_at', NULL, NULL, 'static', NULL, NULL, 'date', NULL, NULL, NULL, 1, 0, NULL, 0, NULL),
(113, 4, 'updated_at', NULL, NULL, 'static', NULL, NULL, 'date', NULL, NULL, NULL, 1, 0, NULL, 0, NULL),
(114, 4, 'country_of_manufacture', NULL, NULL, 'varchar', NULL, NULL, 'select', 'Country of Manufacture', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Source\\Countryofmanufacture', 0, 0, NULL, 0, NULL),
(115, 4, 'quantity_and_stock_status', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Backend\\Stock', 'int', NULL, NULL, 'select', 'Quantity', NULL, 'Magento\\CatalogInventory\\Model\\Source\\Stock', 0, 0, '1', 0, NULL),
(116, 4, 'custom_layout', NULL, NULL, 'varchar', NULL, NULL, 'select', 'New Layout', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Source\\Layout', 0, 0, NULL, 0, NULL),
(117, 4, 'price_type', NULL, NULL, 'int', NULL, NULL, 'boolean', 'Dynamic Price', NULL, NULL, 1, 0, '0', 0, NULL),
(118, 4, 'sku_type', NULL, NULL, 'int', NULL, NULL, 'boolean', 'Dynamic SKU', NULL, NULL, 1, 0, '0', 0, NULL),
(119, 4, 'weight_type', NULL, NULL, 'int', NULL, NULL, 'boolean', 'Dynamic Weight', NULL, NULL, 1, 0, '0', 0, NULL),
(120, 4, 'price_view', NULL, NULL, 'int', NULL, NULL, 'select', 'Price View', NULL, 'Magento\\Bundle\\Model\\Product\\Attribute\\Source\\Price\\View', 1, 0, NULL, 0, NULL),
(121, 4, 'shipment_type', NULL, NULL, 'int', NULL, NULL, 'select', 'Ship Bundle Items', NULL, 'Magento\\Bundle\\Model\\Product\\Attribute\\Source\\Shipment\\Type', 1, 0, '0', 0, NULL),
(122, 4, 'msrp', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Backend\\Price', 'decimal', NULL, NULL, 'price', 'Manufacturer\'s Suggested Retail Price', NULL, NULL, 0, 0, NULL, 0, NULL),
(123, 4, 'msrp_display_actual_price_type', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Backend\\Boolean', 'varchar', NULL, NULL, 'select', 'Display Actual Price', NULL, 'Magento\\Msrp\\Model\\Product\\Attribute\\Source\\Type\\Price', 0, 0, '0', 0, NULL),
(124, 3, 'url_key', NULL, NULL, 'varchar', NULL, NULL, 'text', 'URL Key', NULL, NULL, 0, 0, NULL, 0, NULL),
(125, 3, 'url_path', NULL, NULL, 'varchar', NULL, NULL, 'text', NULL, NULL, NULL, 0, 0, NULL, 0, NULL),
(126, 4, 'url_key', NULL, NULL, 'varchar', NULL, NULL, 'text', 'URL Key', NULL, NULL, 0, 0, NULL, 0, NULL),
(127, 4, 'url_path', NULL, NULL, 'varchar', NULL, NULL, 'text', NULL, NULL, NULL, 0, 0, NULL, 0, NULL),
(128, 4, 'links_purchased_separately', NULL, NULL, 'int', NULL, NULL, NULL, 'Links can be purchased separately', NULL, NULL, 1, 0, NULL, 0, NULL),
(129, 4, 'samples_title', NULL, NULL, 'varchar', NULL, NULL, NULL, 'Samples title', NULL, NULL, 1, 0, NULL, 0, NULL),
(130, 4, 'links_title', NULL, NULL, 'varchar', NULL, NULL, NULL, 'Links title', NULL, NULL, 1, 0, NULL, 0, NULL),
(131, 4, 'links_exist', NULL, NULL, 'int', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '0', 0, NULL),
(132, 4, 'gift_message_available', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Backend\\Boolean', 'varchar', NULL, NULL, 'select', 'Allow Gift Message', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Source\\Boolean', 0, 0, NULL, 0, NULL),
(133, 4, 'swatch_image', NULL, NULL, 'varchar', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Frontend\\Image', 'media_image', 'Swatch', NULL, NULL, 0, 0, NULL, 0, NULL),
(134, 4, 'tax_class_id', NULL, NULL, 'int', NULL, NULL, 'select', 'Tax Class', NULL, 'Magento\\Tax\\Model\\TaxClass\\Source\\Product', 0, 0, '2', 0, NULL),
(135, 4, 'ts_dimensions_length', NULL, NULL, 'decimal', NULL, NULL, 'text', 'Length', NULL, NULL, 0, 0, NULL, 0, NULL),
(136, 4, 'ts_dimensions_width', NULL, NULL, 'decimal', NULL, NULL, 'text', 'Width', NULL, NULL, 0, 0, NULL, 0, NULL),
(137, 4, 'ts_dimensions_height', NULL, NULL, 'decimal', NULL, NULL, 'text', 'Height', NULL, NULL, 0, 0, NULL, 0, NULL),
(138, 3, 'amlanding_is_dynamic', NULL, NULL, 'int', NULL, NULL, NULL, 'Is dynamic category', NULL, NULL, 1, 0, '0', 0, 'Get products by dynamic rules'),
(139, 3, 'amasty_dynamic_conditions', NULL, NULL, 'text', NULL, NULL, NULL, 'Dynamic Products Conditions', NULL, NULL, 1, 0, NULL, 0, NULL),
(140, 3, 'amasty_category_product_sort', NULL, NULL, 'int', NULL, NULL, NULL, 'Category Products Sort', NULL, NULL, 1, 0, NULL, 0, NULL),
(141, 4, 'am_shipping_type', NULL, NULL, 'varchar', NULL, NULL, 'select', 'Shipping Type', '', NULL, 0, 0, '', 0, NULL),
(142, 4, 'amxnotif_hide_alert', NULL, NULL, 'int', NULL, NULL, 'boolean', 'Hide Stock Alert Block', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Boolean', 0, 0, '0', 0, NULL),
(143, 1, 'df', NULL, NULL, 'static', NULL, NULL, 'text', 'Mage2.PRO', NULL, NULL, 0, 0, NULL, 0, NULL),
(144, 4, 'shipping_group', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Shipping Group', '', NULL, 0, 1, '', 0, NULL),
(145, 4, 'condition', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Condition Old', '', NULL, 0, 1, '', 0, NULL),
(154, 4, 'cooling_type', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Cooling Type', '', NULL, 0, 1, '', 0, NULL),
(156, 4, 'dough_capacity', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Dough Capacity', '', NULL, 0, 1, '', 0, NULL),
(157, 4, 'door_type', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Door Type', '', NULL, 0, 1, '', 0, NULL),
(158, 4, 'ean', NULL, NULL, 'varchar', NULL, NULL, 'text', 'EAN', '', NULL, 0, 1, '', 0, NULL),
(159, 4, 'gtin', NULL, NULL, 'varchar', NULL, NULL, 'text', 'GTIN', '', NULL, 0, 1, '', 0, NULL),
(160, 4, 'upc', NULL, NULL, 'varchar', NULL, NULL, 'text', 'UPC', '', NULL, 0, 1, '', 0, NULL),
(161, 4, 'marketplace_title', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Marketplace Title', '', NULL, 0, 1, '', 0, NULL),
(162, 4, 'external_material', NULL, NULL, 'varchar', NULL, NULL, 'text', 'External Material', '', NULL, 0, 1, '', 0, NULL),
(163, 4, 'number_of_doors', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Number of Doors', '', NULL, 0, 1, '', 0, NULL),
(164, 4, 'power', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Power', '', NULL, 0, 1, '', 0, NULL),
(165, 4, 'splashback', NULL, NULL, 'int', NULL, NULL, 'select', 'Splashback', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Table', 0, 1, NULL, 0, NULL),
(166, 4, 'temperature_range', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Temperature Range', '', NULL, 0, 1, '', 0, NULL),
(167, 4, 'warranty', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Warranty', '', NULL, 0, 1, '', 0, NULL),
(168, 4, 'warehouse', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Warehouse', '', NULL, 0, 1, '', 0, NULL),
(169, 4, 'wheels', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Wheels', '', NULL, 0, 1, '', 0, NULL),
(170, 4, 'youtube_id', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Youtube ID', '', NULL, 0, 1, '', 0, NULL),
(171, 4, 'wistia_id', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Wistia ID', '', NULL, 0, 1, '', 0, NULL),
(172, 4, 'ambient_temperature', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Ambient Temperature', '', NULL, 0, 1, '', 0, NULL),
(173, 4, 'display_layers', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Display Layers', '', NULL, 0, 1, '', 0, NULL),
(174, 4, 'energy_consumption', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Energy Consumption', '', NULL, 0, 1, '', 0, NULL),
(176, 4, 'internal_dimensions', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Internal Dimensions', '', NULL, 0, 1, '', 0, NULL),
(177, 4, 'plug_type', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Plug Type', '', NULL, 0, 1, '', 0, NULL),
(178, 4, 'product_dimensions', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Product Dimensions', '', NULL, 0, 1, '', 0, NULL),
(179, 4, 'product_information', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Product Information', '', NULL, 0, 1, '', 0, NULL),
(180, 4, 'supplier', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Supplier', '', NULL, 0, 1, '', 0, NULL),
(181, 4, 'glass_shape', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Glass Shape', '', NULL, 0, 1, '', 0, NULL),
(182, 4, 'temperature_type', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Temperature Type', '', NULL, 0, 1, '', 0, NULL),
(183, 4, 'magebees_quote', NULL, NULL, 'int', NULL, NULL, 'boolean', 'Apply Add To Quote', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Boolean', 0, 0, '1', 0, NULL),
(185, 4, 'size', NULL, NULL, 'int', NULL, NULL, 'select', 'Size', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Table', 0, 1, NULL, 0, NULL),
(186, 4, 'mpn', NULL, NULL, 'varchar', NULL, NULL, 'text', 'MPN', '', NULL, 0, 1, '', 0, NULL),
(187, 4, 'flour_capacity', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Flour Capacity', '', NULL, 0, 1, '', 0, NULL),
(188, 4, 'simple_preselect', NULL, NULL, 'text', NULL, NULL, 'text', 'Simple Preselect', NULL, NULL, 0, 1, NULL, 0, NULL),
(189, 4, 'amasty_conf_flipper_image', NULL, NULL, 'varchar', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Frontend\\Image', 'media_image', 'Flipper Image', NULL, NULL, 0, 0, NULL, 0, NULL),
(190, 4, 'amasty_conf_matrix', NULL, NULL, 'int', NULL, NULL, 'boolean', 'Display Last Attribute in Rows', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Boolean', 0, 0, '0', 0, NULL),
(191, 3, 'thumbnail', NULL, 'Magento\\Catalog\\Model\\Category\\Attribute\\Backend\\Image', 'varchar', NULL, NULL, 'image', 'Thumbnail', NULL, NULL, 0, 0, NULL, 0, NULL),
(193, 4, 'product_height', NULL, NULL, 'int', NULL, NULL, 'select', 'Product Height', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Table', 0, 1, '', 0, NULL),
(194, 4, 'product_width', NULL, NULL, 'int', NULL, NULL, 'select', 'Product Width', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Table', 0, 1, '', 0, NULL),
(195, 4, 'product_depth', NULL, NULL, 'int', NULL, NULL, 'select', 'Product Depth', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Table', 0, 1, NULL, 0, NULL),
(196, 4, 'product_weight', NULL, NULL, 'int', NULL, NULL, 'select', 'Product Weight', '', 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Table', 0, 1, '', 0, NULL),
(197, 4, 'packed_height', NULL, NULL, 'int', NULL, NULL, 'select', 'Packed Height', '', 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Table', 0, 1, '', 0, NULL),
(198, 4, 'packed_width', NULL, NULL, 'int', NULL, NULL, 'select', 'Packed Width', '', 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Table', 0, 1, '', 0, NULL),
(199, 4, 'packed_depth', NULL, NULL, 'int', NULL, NULL, 'select', 'Packed Depth', '', 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Table', 0, 1, '', 0, NULL),
(200, 4, 'packed_weight', NULL, NULL, 'int', NULL, NULL, 'select', 'Packed Weight', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Table', 0, 1, NULL, 0, NULL),
(201, 4, 'capacity', NULL, NULL, 'int', NULL, NULL, 'select', 'Capacity', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Table', 0, 1, '', 0, NULL),
(202, 4, 'fuel', NULL, NULL, 'int', NULL, NULL, 'select', 'Fuel', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Table', 0, 1, NULL, 0, NULL),
(204, 4, 'xero_sales_account_code', NULL, NULL, 'int', NULL, NULL, 'select', 'Xero Sales Account Code', NULL, 'Fooman\\Connect\\Model\\System\\SalesProductAccountOptions', 0, 0, NULL, 0, NULL),
(205, 4, 'rrp', NULL, NULL, 'varchar', NULL, NULL, 'text', 'RRP', '', NULL, 0, 1, '', 0, NULL),
(206, 4, 'am_product_width', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Backend\\Price', 'decimal', NULL, NULL, 'price', 'Product Width', NULL, NULL, 0, 1, NULL, 0, NULL),
(207, 4, 'am_product_depth', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Backend\\Price', 'decimal', NULL, NULL, 'price', 'Product Depth', NULL, NULL, 0, 1, NULL, 0, NULL),
(208, 4, 'am_product_height', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Backend\\Price', 'decimal', NULL, NULL, 'price', 'Product Height', NULL, NULL, 0, 1, NULL, 0, NULL),
(209, 4, 'asin', NULL, NULL, 'varchar', NULL, NULL, 'text', 'ASIN', '', NULL, 0, 1, '', 0, NULL),
(210, 3, 'use_name_in_product_search', NULL, NULL, 'int', NULL, NULL, 'select', 'Use category name in product search', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Boolean', 1, 0, '1', 0, 'If the category name is used for fulltext search on products.'),
(211, 3, 'is_virtual_category', NULL, NULL, 'int', NULL, NULL, NULL, 'Is virtual category', NULL, NULL, 0, 0, '0', 0, 'Is the category is virtual or not ?'),
(212, 3, 'virtual_category_root', NULL, NULL, 'int', NULL, NULL, NULL, 'Virtual category root', NULL, NULL, 0, 0, NULL, 0, 'Root display of the virtual category (usefull to display a facet category on virtual).'),
(213, 3, 'virtual_rule', NULL, NULL, 'text', NULL, NULL, NULL, 'Virtual rule', NULL, NULL, 0, 0, NULL, 0, 'Virtual category rule.'),
(214, 3, 'use_store_positions', NULL, NULL, 'int', NULL, NULL, 'select', 'Use store positions', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Boolean', 1, 0, '0', 0, 'Use store positions.'),
(215, 4, 'catch_product_id', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Catch Product ID', '', NULL, 0, 1, '', 0, NULL),
(216, 4, 'ca_is_in_relationship', NULL, NULL, 'int', NULL, NULL, 'select', 'Is In Relationship', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Boolean', 0, 0, '0', 0, NULL),
(217, 4, 'ca_relationship_name', NULL, NULL, 'text', NULL, NULL, 'text', 'Relationship Name', NULL, NULL, 0, 0, '0', 0, NULL),
(218, 4, 'ca_is_parent', NULL, NULL, 'int', NULL, NULL, 'select', 'Is Parent', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Boolean', 0, 0, '0', 0, NULL),
(219, 4, 'ca_parent_sku', NULL, NULL, 'text', NULL, NULL, 'text', 'Parent Sku', NULL, NULL, 0, 0, '0', 0, NULL),
(220, 4, 'ca_images', NULL, NULL, 'text', NULL, NULL, 'textarea', 'ChannelAdvisor Images', NULL, NULL, 0, 0, NULL, 0, NULL),
(221, 4, 'catch_shipping_group', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Catch Shipping Group', NULL, NULL, 0, 1, NULL, 0, NULL),
(222, 4, 'catch_account', NULL, NULL, 'varchar', NULL, NULL, 'text', 'Catch Account', NULL, NULL, 0, 1, NULL, 0, NULL),
(223, 4, 'condition_2', NULL, NULL, 'int', NULL, NULL, 'select', 'Condition', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Table', 0, 1, '', 0, NULL),
(226, 4, 'capacity_liters', NULL, 'Magento\\Catalog\\Model\\Product\\Attribute\\Backend\\Price', 'decimal', NULL, NULL, 'price', 'capacity_liters', NULL, NULL, 0, 1, NULL, 0, NULL),
(227, 4, 'door_fill_type', NULL, NULL, 'int', NULL, NULL, 'select', 'Door Fill Type', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Table', 0, 1, '', 0, NULL),
(228, 4, 'door_type_2', NULL, NULL, 'int', NULL, NULL, 'select', 'Door Type', NULL, 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Table', 0, 1, '', 0, NULL),
(229, 4, 'ebay_youtube', NULL, NULL, 'text', NULL, NULL, 'textarea', 'ebay_youtube', NULL, NULL, 0, 1, NULL, 0, NULL),
(230, 4, 'ebay_category_id', NULL, NULL, 'varchar', NULL, NULL, 'text', 'eBay Category ID', NULL, NULL, 0, 1, NULL, 0, NULL),
(231, 4, 'options', NULL, NULL, 'int', NULL, NULL, 'select', 'Options', '', 'Magento\\Eav\\Model\\Entity\\Attribute\\Source\\Table', 0, 1, '', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eav_attribute`
--
ALTER TABLE `eav_attribute`
  ADD PRIMARY KEY (`attribute_id`),
  ADD UNIQUE KEY `EAV_ATTRIBUTE_ENTITY_TYPE_ID_ATTRIBUTE_CODE` (`entity_type_id`,`attribute_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eav_attribute`
--
ALTER TABLE `eav_attribute`
  MODIFY `attribute_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Attribute Id', AUTO_INCREMENT=232;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `eav_attribute`
--
ALTER TABLE `eav_attribute`
  ADD CONSTRAINT `EAV_ATTRIBUTE_ENTITY_TYPE_ID_EAV_ENTITY_TYPE_ENTITY_TYPE_ID` FOREIGN KEY (`entity_type_id`) REFERENCES `eav_entity_type` (`entity_type_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
