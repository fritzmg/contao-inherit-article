<?php

/**
 * Contao Open Source CMS
 *
 * Extension to replace the ArticleModel in order to allow inheritable articles
 * 
 * @copyright inspiredminds 2015
 * @package   inherit_article
 * @link      http://www.inspiredminds.at
 * @author    Fritz Michael Gschwantner <fmg@inspiredminds.at>
 * @license   GPL-2.0
 */


/**
 * Add palettes to tl_article
 */
$GLOBALS['TL_DCA']['tl_article']['palettes']['default'] = str_replace( '{expert_legend:hide},guests,cssID', '{expert_legend:hide},guests,inherit,cssID', $GLOBALS['TL_DCA']['tl_article']['palettes']['default']);

/**
 * Add fields to tl_article
 */
$GLOBALS['TL_DCA']['tl_article']['fields']['inherit'] = array
(
	'exclude'   => true,
	'label'     => &$GLOBALS['TL_LANG']['tl_article']['inherit'],
	'inputType' => 'checkbox',
	'eval'      => array('tl_class'=>'w50'),
	'sql'       => "char(1) NOT NULL default ''"
);