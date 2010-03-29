<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * XLite_Module_FeaturedProducts_Controller_Admin_Categories
 * 
 * @package    XLite
 * @subpackage Controller
 * @since      3.0.0
 */
class XLite_Module_FeaturedProducts_Controller_Admin_Categories extends XLite_Controller_Admin_Categories implements XLite_Base_IDecorator
{
    public function __construct(array $params)
    {
        if (!isset($_REQUEST["search_category"])) {
            $_REQUEST["search_category"] = $_REQUEST["category_id"];
        }    
    }

	public function action_add_featured_products()
	{
		if (isset($_POST["product_ids"])) {
			$products = array();
			foreach ($_POST["product_ids"] as $product_id => $value) {
				$products[] = new XLite_Model_Product($product_id);
			}
			$category = new XLite_Model_Category($this->category_id);
			$category->addFeaturedProducts($products);
		}
	}

    public function getProducts()
    {
        if ($this->get("mode") != "search") {
            return array();
        }
        $p = new XLite_Model_Product();
        $result = $p->advancedSearch($this->substring,
                                      $this->search_productsku,
                                      $this->search_category,
                                      $this->subcategory_search);
        $this->productsFound = count($result);
        return $result;
    }

	/**
	 * Process action 'update_featured_products'
	 * 
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function action_update_featured_products()
	{
		// Delete featured products if it was requested
		$deleteProducts = XLite_Core_Request::getInstance()->delete;
		
		if (!is_null($deleteProducts) && is_array($deleteProducts) && !empty($deleteProducts)) {

			foreach (array_keys($deleteProducts) as $productId) {
				$products[] = new XLite_Model_Product($productId);
			}

			$category = new XLite_Model_Category(XLite_Core_Request::getInstance()->category_id);

			if ($category->isExists() || 0 === intval(XLite_Core_Request::getInstance()->category_id)) {
				$category->deleteFeaturedProducts($products);
			}
		}

		// Update order_by of featured products list is it was requested
		$orderProducts = XLite_Core_Request::getInstance()->orderbys;

		if (!is_null($orderProducts) && is_array($orderProducts) && !empty($orderProducts)) {

			foreach ($orderProducts as $productId => $orderBy) {
				$fp = new XLite_Module_FeaturedProducts_Model_FeaturedProduct();
				$fp->set('category_id', XLite_Core_Request::getInstance()->category_id);
				$fp->set('product_id', $productId);
				$fp->set('order_by', $orderBy);
				$fp->update();
			}
		}
	}
}
