<?php

/**
 * Contao Open Source CMS
 *
 * Extension to replace the ArticleModel in order to allow inheritable articles
 * 
 * @copyright inspiredminds 2016
 * @package   inherit_article
 * @link      http://www.inspiredminds.at
 * @author    Fritz Michael Gschwantner <fmg@inspiredminds.at>
 * @license   GPL-2.0
 */


$GLOBALS['TL_LANG']['tl_article']['inherit_legend'] = 'Inheritance';
$GLOBALS['TL_LANG']['tl_article']['inherit'] = array('Inherit','Inherit the article downwards in the page hierarchy.');
$GLOBALS['TL_LANG']['tl_article']['inheritLevel'] = array('Maximum inheritance','Number of pages the article is inherited downwards in the hierarchy (0 = all).');
$GLOBALS['TL_LANG']['tl_article']['inheritPriority'] = array('Priority','Determines the order of the combined articles (use negative values to put inherited articles to the bottom).');
