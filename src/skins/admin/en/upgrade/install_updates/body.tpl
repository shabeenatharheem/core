{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Updates
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

{* :TODO: integrate design *}
{* :TODO: of course, devide it into parts (lists) *}

<widget class="\XLite\View\Upgrade\SelectCoreVersion\Button" />

<form action="admin.php" method="post">
  <input type="hidden" name="target" value="upgrade">

  <div FOREACH="getUpgradeEntries(),entry">

    {entry.getName()}

    <span IF="isModule(entry)">
      <span>{entry.getAuthor()}</span>
      <span IF="entry.isEnabled()">{t(#Enabled#)}</span>
      <span IF="!entry.isEnabled()">{t(#Disabled#)}</span>
    </span>

    {entry.getVersionNew()}

  </div>

  <widget class="\XLite\View\Button\Submit" label="Install updates" />
</form>