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

$GLOBALS['BE_MOD']['content']['cFaqordion']	= array (
  'tables' 		=> array('tl_cfaq_category','tl_cfaq_items'),
  'icon'			=> 'system/modules/faq/assets/icon.gif'
);

$GLOBALS['FE_MOD']['cFaqordion'] = array
(
  'cFaqordion' => 'ContentFaqordion',
);