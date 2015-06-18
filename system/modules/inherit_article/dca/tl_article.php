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
$GLOBALS['TL_DCA']['tl_article']['palettes']['default'] = str_replace( ',space;', ',space,inherit,inheritLevel;', $GLOBALS['TL_DCA']['tl_article']['palettes']['default']);

/**
 * Add fields to tl_article
 */
$GLOBALS['TL_DCA']['tl_article']['fields']['inherit'] = array
(
	'exclude'   => true,
	'label'     => &$GLOBALS['TL_LANG']['tl_article']['inherit'],
	'inputType' => 'checkbox',
	'eval'      => array('tl_class'=>'w50 m12'),
	'sql'       => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_article']['fields']['inheritLevel'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_article']['inheritLevel'],
	'exclude'   => true,
	'inputType' => 'text',
	'eval'      => array('maxlength'=>5, 'rgxp'=>'digit', 'tl_class'=>'w50'),
	'sql'       => "smallint(5) unsigned NOT NULL default '0'"
);
