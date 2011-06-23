{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product element
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 * @ListChild (list="product.modify.list", weight="850")
 *}

<tr>
  <td class="name-attribute">{t(#Arrival date#)}</td>
  <td>&nbsp;</td>
  <td class="value-attribute">
    <widget class="\XLite\View\DatePicker" field="{getNamePostedData(#arrivalDate#)}" value="{product.getArrivalDate()}" />
  </td>
</tr>
