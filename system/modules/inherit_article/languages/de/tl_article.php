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


$GLOBALS['TL_LANG']['tl_article']['inherit_legend'] = 'Vererbung';
$GLOBALS['TL_LANG']['tl_article']['inherit'] = array('Vererben','Artikel in der Seitenstruktur nach unten vererben.');
$GLOBALS['TL_LANG']['tl_article']['inheritLevel'] = array('Maximale Vererbung','Anzahl an Seitenebenen, die der Artikel maximal nach unten vererbt wird (0 = kein Limit).');
$GLOBALS['TL_LANG']['tl_article']['inheritAfter'] = array('Am Ende hinzufügen','Fügt diesen Artikel hinter den anderen an, wenn er vererbt wird.');
