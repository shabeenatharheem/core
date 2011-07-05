{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Category selection dropdown box template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<select name="{getParam(#fieldName#)}" size="1" {if:nonFixed} style="width:200pt" {else:}  class="FixedSelect" {end:}  >
   <option value="" IF="getParam(#allOption#)">{t(#All#)}</option>
   <option value="" IF="getParam(#noneOption#)">{t(#None#)}</option>
   <option value="" IF="getParam(#rootOption#)" class="CenterBorder">{t(#Root level#)}</option>
	{foreach:getCategories(),key,category}
    <option
        IF="!category.category_id=getParam(#currentCategoryId#)"
        value="{category.category_id:r}"
        selected="{isCategorySelected(category)}"
        style="padding-left: {getIndentation(category,15)}px;">{getCategoryPath(category):h}</option>
	{end:}
  <option value="" IF="isDisplayNoCategories()">{t(#-- No categories --#)}</option>
</select>
