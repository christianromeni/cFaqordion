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
 * Add pallettes to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['cFaqordion'] = '{title_legend},name,headline,type;{config_legend},show_list,text_empty,text_page,text_of,faq_page_limit;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_module']['fields']['show_list'] = array(
  'label'                   => &$GLOBALS['TL_LANG']['tl_module']['show_list'],
  'inputType'               => 'select',
  'options'									=> array(true => "Liste", false => "Seiteninfo"),
  'eval'                    => array('tl_class'=>'w50'),
  'sql'                     => "int(1) NOT NULL",
);

$GLOBALS['TL_DCA']['tl_module']['fields']['text_empty'] = array(
  'label'                   => &$GLOBALS['TL_LANG']['tl_module']['text_empty'],
  'inputType'               => 'text',
  'eval'                    => array('tl_class'=>'w50'),
  'sql'                     => "varchar(20) NOT NULL default ''",
);
$GLOBALS['TL_DCA']['tl_module']['fields']['text_page'] = array(
  'label'                   => &$GLOBALS['TL_LANG']['tl_module']['text_page'],
  'inputType'               => 'text',
  'eval'                    => array('tl_class'=>'w50'),
  'sql'                     => "varchar(20) NOT NULL default ''",
);
$GLOBALS['TL_DCA']['tl_module']['fields']['text_of'] = array(
  'label'                   => &$GLOBALS['TL_LANG']['tl_module']['text_of'],
  'inputType'               => 'text',
  'eval'                    => array('tl_class'=>'w50'),
  'sql'                     => "varchar(20) NOT NULL default ''",
);
$GLOBALS['TL_DCA']['tl_module']['fields']['faq_page_limit'] = array(
  'label'                   => &$GLOBALS['TL_LANG']['tl_module']['faq_page_limit'],
  'inputType'               => 'text',
  'eval'                    => array('tl_class'=>'w50'),
  'sql'                     => "varchar(4) NOT NULL default ''",
);