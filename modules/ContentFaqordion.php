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
 * Namespace
 */
namespace cFaqordion;

class ContentFaqordion extends \Module {

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_faqordion';

    /**
     * Display a wildcard in the back end
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            /** @var \BackendTemplate|object $objTemplate */
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### FAQ Accordion ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        return parent::generate();
    }

    /**
     * Generate the content element
     */
    protected function compile() {
        $GLOBALS['TL_JAVASCRIPT'][] = '/system/modules/cFaqordion/assets/cfaqordion.js|static';
        $GLOBALS['TL_CSS'][] = '/system/modules/cFaqordion/assets/cfaqordion.scss|static';
        $arrItems = array();
        $objCategories = CfaqCategoryModel::findAll(array('column' => 'visible','value' => 1, 'order' => 'sorting ASC'));
        foreach ($objCategories as $key => $category) {
            $objItems[$category->alias] = CfaqItemsModel::findPublishedByPid($category->id);
            if ($objItems[$category->alias] != NULL) {
                while($objItems[$category->alias]->next()) {
                    $arrItems[$category->alias][] = $objItems[$category->alias]->row();
                }
                $arrItemsSplitted[$category->alias] = array_chunk($arrItems[$category->alias], ($this->faq_page_limit ?: 10), true);
            }
        }

        $text["prev"]  = $GLOBALS['TL_LANG']['MSC']['previous'];
        $text["next"]  = $GLOBALS['TL_LANG']['MSC']['next'];
        $text["empty"] = ($this->text_empty ?: "Keine Inhalte vorhanden");
        $text["page"]  = ($this->text_page ?: "Seite");
        $text["of"]    = ($this->text_of ?: "von");
        $showlist      = $this->show_list;

        $this->Template->categories = $objCategories;
        $this->Template->items = $arrItemsSplitted;
        $this->Template->text = $text;
        $this->Template->showlist = $showlist;
    }
}
