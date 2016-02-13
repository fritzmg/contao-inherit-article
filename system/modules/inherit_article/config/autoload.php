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


/**
 * Register the classes
 */
if( version_compare( VERSION, '3.5', '>=' ) )
	ClassLoader::addClasses( array('Contao\ArticleModel' => 'system/modules/inherit_article/models/ArticleModel_C35.php') );
else
	ClassLoader::addClasses( array('Contao\ArticleModel' => 'system/modules/inherit_article/models/ArticleModel.php') );
