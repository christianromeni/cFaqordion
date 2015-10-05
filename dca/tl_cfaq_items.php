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
 * Table tl_cfaq_items
 */

$GLOBALS['TL_DCA']['tl_cfaq_items'] = array(
	'config' => array(
		'dataContainer'		=> 'Table',
		'enableVersioning'	=> true,
		'ptable'			=> 'tl_cfaq_category',
		'sql' => array(
			'keys' => array(
				'id'	=> 'primary',
				'pid'	=> 'index',
				'alias'	=> 'index',
			)
		)
	),

	// List
	'list' => array(
		'sorting' => array(
			'mode'                    => 4,
			'headerFields'            => array('category'),
			'fields'                  => array('sorting'),
			'child_record_callback'   => array('tl_cfaq_items', 'list_items'),
			'child_record_class'      => 'no_padding',
			'flag'                    => 1,
			'disableGrouping'		  => true,
			'panelLayout'             => 'filter;search,limit'
		),
		'label' => array(
			'fields'                  => array('headline'),
			'format'                  => '<span class="projekt" style="font-weight:bold;">%s</span>'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
        'operations' => array(
            'edit' => array(
                'label' => &$GLOBALS['TL_LANG']['tl_cfaq_items']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif'
            ),
            'copy' => array(
                'label'           => &$GLOBALS['TL_LANG']['tl_cfaq_items']['copy'],
                'href'            => 'act=copy',
                'icon'            => 'copy.gif',
                'button_callback' => array('tl_cfaq_items', 'copyCategory')
            ),
            'delete' => array(
                'label'      => &$GLOBALS['TL_LANG']['tl_cfaq_items']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'toggle' => array(
                'label'           => &$GLOBALS['TL_LANG']['tl_cfaq_items']['toggle'],
                'icon'            => 'visible.gif',
                'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => array('tl_cfaq_items', 'iconToggle')
            ),
            'show' => array(
                'label'      => &$GLOBALS['TL_LANG']['tl_cfaq_items']['show'],
                'href'       => 'act=show',
                'icon'       => 'show.gif',
                'attributes' => 'style="margin-right:3px"'
            )
        )
	),

	// Palettes
	'palettes' => array
	(
		'default' => '{details_legend},headline,alias,text;{visibility_legend},visible;'
	),

	// Fields
	'fields' => array(
		'id' => array(
			'sql' => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array(
				'foreignKey'	=> 'tl_cfaq_category.id',
				'sql'					=> "int(10) unsigned NOT NULL default '0'",
				'relation'		=> array('type'=>'belongsTo', 'load'=>'eager')
		),
    'sorting' => array(
        'sql' => "int(10) unsigned NOT NULL default '0'"
    ),
    'tstamp' => array(
        'sql' => "int(10) unsigned NOT NULL default '0'"
    ),
		'headline' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_cfaq_items']['headline'],
			'inputType'	=> 'text',
			'exclude'	=> true,
			'sorting'	=> true,
			'flag'		=> 1,
			'search'	=> true,
			'eval'		=> array(
				'mandatory'	=>true,
				'maxlength'	=>255,
				'tl_class'	=>'w50'
			),
			'sql'		=> "varchar(255) NOT NULL default ''"
		),
		'alias' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_cfaq_items']['alias'],
			'exclude'		=> true,
			'inputType'		=> 'text',
			'search'		=> true,
			'eval'			=> array(
				'rgxp'				=>'folderalias',
				'doNotCopy'			=>true,
				'spaceToUnderscore'	=>true,
				'maxlength'			=>128,
				'tl_class'			=>'w50'
			),
			'save_callback'	=> array(
				array('tl_cfaq_items', 'generateAlias'),
			),
			'sql'			=> "varbinary(128) NOT NULL default ''"
		),
		'text' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_cfaq_items']['text'],
			'inputType'	=> 'textarea',
			'exclude'	=> true,
			'flag'		=> 1,
			'search'	=> true,
			'eval'		=> array(
				'maxlength'	=>2000,
				'tl_class'	=>'w100 clr',
				'rte'		=>'tinyMCE'
			),
			'sql'		=> "varchar(2000) NOT NULL default ''"
		),
	    'visible' => array(
	        'label' => &$GLOBALS['TL_LANG']['tl_cfaq_items']['visible'],
	        'inputType' => 'checkbox',
	        'flag' => 1,
	        'search' => false,
	        'eval' => array(
	            'tl_class' => 'w50'
	        ),
	        'sql' => "char(1) NOT NULL default ''"
	    )
	)
);

/**
 * Class tl_cfaq_items
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class tl_cfaq_items extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

    /**
     * Return the copy category button
     */
    public function copyCategory($row, $href, $label, $title, $icon, $attributes)
    {
    $this->import('BackendUser', 'User');
        return $this->User->hasAccess('create', 'newp') ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
    }

    /**
    * Ändert das Aussehen des Toggle-Buttons.
    */
    public function iconToggle($row, $href, $label, $title, $icon, $attributes){
        $this->import('BackendUser', 'User');
        if (strlen($this->Input->get('tid'))){
            $this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 0));
            $this->redirect($this->getReferer());
        }
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_cfaq_items::visible', 'alexf')){
            return '';
        }

        $href .= '&amp;id='.$this->Input->get('id').'&amp;tid='.$row['id'].'&amp;state='.$row[''];

        if (!$row['visible']){
            $icon = 'invisible.gif';
        }

        return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
    }

    /**
    * Toggle the visibility of an element
    */
    public function toggleVisibility($intId, $blnPublished){
        $this->import('BackendUser', 'User');
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_cfaq_items::visible', 'alexf')){
            $this->log('Not enough permissions to show/hide record ID "'.$intId.'"', 'tl_cfaq_items toggleVisibility', TL_ERROR);
            $this->redirect('contao/main.php?act=error');
        }

        $this->createInitialVersion('tl_cfaq_items', $intId);

        if (is_array($GLOBALS['TL_DCA']['tl_cfaq_items']['fields']['visible']['save_callback'])){
            foreach ($GLOBALS['TL_DCA']['tl_cfaq_items']['fields']['visible']['save_callback'] as $callback){
                $this->import($callback[0]);
                $blnPublished = $this->$callback[0]->$callback[1]($blnPublished, $this);
            }
        }

        // Update the database
        $this->Database->prepare("UPDATE tl_cfaq_items SET tstamp=". time() .", visible='" . ($blnPublished ? '' : '1') . "' WHERE id=?")->execute($intId);
        $this->createNewVersion('tl_cfaq_items', $intId);
    }

	/**
	 * List items
	 *
	 * @param array $row
	 *
	 * @return string
	 */
	public function list_items($row)
	{
		return '<h6 class="tl_content_left">'. $row['headline'] . '</h6><div class="tl_content_left">' . $row['text'] . '</div>';
	}


    /**
    * Auto-generate a alias if it has not been set yet
    */
    public function generateAlias($varValue, DataContainer $dc){
        // Generate an alias if there is none
        if ($varValue == '') {
            $varValue = standardize(String::restoreBasicEntities($dc->activeRecord->headline));
        }
        return $varValue;
    }

}
