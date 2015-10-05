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

/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'cFaqordion',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Elements
	'cFaqordion\ContentFaqordion'		=> 'system/modules/cFaqordion/modules/ContentFaqordion.php',

	// Models
	'cFaqordion\CfaqCategoryModel'	=> 'system/modules/cFaqordion/models/CfaqCategoryModel.php',
	'cFaqordion\CfaqItemsModel'			=> 'system/modules/cFaqordion/models/CfaqItemsModel.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_faqordion' => 'system/modules/cFaqordion/templates',
));
