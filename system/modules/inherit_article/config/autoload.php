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
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Models
	'Contao\ArticleModel' => 'system/modules/inherit_article/models/ArticleModel.php'
));