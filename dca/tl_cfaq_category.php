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
 * Table tl_cfaq_category
 */

$GLOBALS['TL_DCA']['tl_cfaq_category'] = array(
    'config' => array(
        'dataContainer'    => 'Table',
        'ctable'           => array('tl_cfaq_items'),
        'switchToEdit'     => true,
        'enableVersioning' => true,
        'sql'              => array(
            'keys' => array(
                'id'    => 'primary',
                'alias' => 'index'
            )
        )
    ),
    'list' => array(
        'sorting' => array(
            'mode'            => 5,
            'icon'            => 'system/modules/faq/assets/icon.gif',
            'fields'          => array('category'),
            'flag'            => 1,
            'disableGrouping' => true,
            'panelLayout'     => 'search',
        ),
        'label' => array(
            'fields'         => array('category'),
            'format'         => '%s',
            'label_callback' => array('tl_cfaq_category', 'addIcon')
        ),
        'global_operations' => array(
            'all' => array(
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array(
            'edit' => array(
                'label' => &$GLOBALS['TL_LANG']['tl_cfaq_category']['edit'],
                'href'  => 'table=tl_cfaq_items',
                'icon'  => 'edit.gif'
            ),
            'editheader' => array(
                'label' => &$GLOBALS['TL_LANG']['tl_cfaq_category']['editheader'],
                'href'  => 'act=edit',
                'icon'  => 'header.gif'
            ),
            'copy' => array(
                'label'           => &$GLOBALS['TL_LANG']['tl_cfaq_category']['copy'],
                'href'            => 'act=copy',
                'icon'            => 'copy.gif',
                'button_callback' => array('tl_cfaq_category', 'copyCategory')
            ),
            'cut' => array(
                'label'               => &$GLOBALS['TL_LANG']['tl_cfaq_category']['cut'],
                'href'                => 'act=paste&amp;mode=cut',
                'icon'                => 'cut.gif',
                'attributes'          => 'onclick="Backend.getScrollOffset()"',
                'button_callback'     => array('tl_cfaq_category', 'cutCategory')
            ),
            'delete' => array(
                'label'      => &$GLOBALS['TL_LANG']['tl_cfaq_category']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'toggle' => array(
                'label'           => &$GLOBALS['TL_LANG']['tl_cfaq_category']['toggle'],
                'icon'            => 'visible.gif',
                'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => array('tl_cfaq_category', 'iconToggle')
            ),
            'show' => array(
                'label'      => &$GLOBALS['TL_LANG']['tl_cfaq_category']['show'],
                'href'       => 'act=show',
                'icon'       => 'show.gif',
                'attributes' => 'style="margin-right:3px"'
            )
        )
    ),
    'palettes' => array(
        'default' => '{details_legend},category,alias;{visibility_legend},visible;'
    ),
    'fields' => array(
        'id' => array(
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'pid' => array(
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'sorting' => array(
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'tstamp' => array(
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'category' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_cfaq_category']['category'],
            'inputType' => 'text',
            'sorting'   => true,
            'flag'      => 1,
            'search'    => true,
            'eval'      => array(
                'mandatory' => true,
                'unique'    => true,
                'maxlength' => 255,
                'tl_class'  => 'w50'
            ),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'alias' => array(
            'label'         => &$GLOBALS['TL_LANG']['tl_cfaq_category']['alias'],
            'inputType'     => 'text',
            'exclude'       => true,
            'search'        => true,
            'eval'          => array(
                'rgxp'              => 'folderalias',
                'doNotCopy'         => true,
                'spaceToUnderscore' => true,
                'maxlength'         => 255,
                'tl_class'          => 'w50'
            ),
            'save_callback' => array(array('tl_cfaq_category','generateAlias')),
            'sql'           => "varbinary(255) NOT NULL default ''"
        ),
        'visible' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_cfaq_category']['visible'],
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
 * Class tl_cfaq_category
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */

class tl_cfaq_category extends Backend
{
    public function __construct() {
        parent::__construct();
    }

    /**
     * Return the copy category button
     */
    public function copyCategory($row, $href, $label, $title, $icon, $attributes){
        $this->import('BackendUser', 'User');
        return $this->User->hasAccess('create', 'newp') ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
    }

    /**
     * Return the cut page button
     */
    public function cutCategory($row, $href, $label, $title, $icon, $attributes){
        $this->import('BackendUser', 'User');
        return ($this->User->isAdmin || ($this->User->hasAccess($row['type'], 'alpty') && $this->User->isAllowed(2, $row))) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
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
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_cfaq_category::visible', 'alexf')){
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
        if (!$this->User->isAdmin && !$this->User->hasAccess('tl_cfaq_category::visible', 'alexf')){
            $this->log('Not enough permissions to show/hide record ID "'.$intId.'"', 'tl_cfaq_category toggleVisibility', TL_ERROR);
            $this->redirect('contao/main.php?act=error');
        }

        $this->createInitialVersion('tl_cfaq_category', $intId);

        if (is_array($GLOBALS['TL_DCA']['tl_cfaq_category']['fields']['visible']['save_callback'])){
            foreach ($GLOBALS['TL_DCA']['tl_cfaq_category']['fields']['visible']['save_callback'] as $callback){
                $this->import($callback[0]);
                $blnPublished = $this->$callback[0]->$callback[1]($blnPublished, $this);
            }
        }

        // Update the database
        $this->Database->prepare("UPDATE tl_cfaq_category SET tstamp=". time() .", visible='" . ($blnPublished ? '' : '1') . "' WHERE id=?")->execute($intId);
        $this->createNewVersion('tl_cfaq_category', $intId);
    }

    /**
    * Auto-generate a alias if it has not been set yet
    */
    public function generateAlias($varValue, DataContainer $dc){
        // Generate an alias if there is none
        if ($varValue == '') {
            $varValue = standardize(String::restoreBasicEntities($dc->activeRecord->category));
        }
        return $varValue;
    }

    /**
    * Add an icon to each category
    */
    public function addIcon($row, $label, DataContainer $dc=null, $imageAttribute='', $blnReturnImage=false, $blnProtected=false) {
        $return = '<span style="display:inline-block;width:4px;height:4px;vertical-align:middle;background:#ffc900;"></span><span style="display:inline-block;vertical-align:middle;text-indent:5px;">'.$label.'</span>';
        return $return;
    }
}