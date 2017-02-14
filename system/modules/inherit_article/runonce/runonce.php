<?php

/**
 * Contao Open Source CMS
 *
 * Extension to replace the ArticleModel in order to allow inheritable articles
 * 
 * @copyright inspiredminds 2016-2017
 * @package   inherit_article
 * @link      http://www.inspiredminds.at
 * @author    Fritz Michael Gschwantner <fmg@inspiredminds.at>
 * @license   GPL-2.0
 */


/**
 * Runonce for autoupdate
 */
class InheritArticleRunOnce
{
    public function run()
    {
        // get the database
        $objDb = \Database::getInstance();

        if( $objDb->tableExists('tl_article') ) 
        {
            if( $objDb->fieldExists('inheritAfter', 'tl_article') && !$objDb->fieldExists('inheritPriority', 'tl_article') )
            {
                // create field
                $objDb->execute("ALTER TABLE `tl_article` ADD `inheritPriority` smallint(5) NOT NULL default '0'");

                if( $objDb->fieldExists('inheritPriority', 'tl_article', true) )
                {
                    $objDb->execute("UPDATE tl_article SET inheritPriority = '-1' WHERE inheritAfter = '1' AND inherit = '1'");
                    \System::log('Successfully migrated inherit_article settings from <1.3.0 to >=1.3.0.',__METHOD__,TL_GENERAL);
                }
            }
        }
    }
}

$objInheritArticleRunOnce = new InheritArticleRunOnce();
$objInheritArticleRunOnce->run();
