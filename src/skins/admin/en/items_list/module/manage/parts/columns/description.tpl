{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Modules list
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 * @ListChild (list="itemsList.module.manage.columns", weight="70")
 * @ListChild (list="itemsList.module.install.columns", weight="70")
 *}
<td class="module-description-section">
  {displayNestedViewListContent(#module-description-section#,_ARRAY_(#module#^module))}
</td>
