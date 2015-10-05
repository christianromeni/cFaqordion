<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * @package   cFaqordion
 * @author    Christian Romeni  <c.romeni@brainfactory.de>
 * @link      https://brainfactory.de
 * @license   GNU
 * @copyright BrainFactory
 */

namespace cFaqordion;

class CfaqItemsModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_cfaq_items';

	public static function findPublishedByPid($intId, array $arrOptions=array('order' => 'sorting ASC'))
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=?");
		$arrColumns[] = " $t.visible='1'";

		return static::findBy($arrColumns, $intId, $arrOptions);
	}

}
