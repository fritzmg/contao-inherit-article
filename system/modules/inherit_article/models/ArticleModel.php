<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Contao;


/**
 * Reads and writes articles
 *
 * @author    Leo Feyer <https://github.com/leofeyer>
 * @author    Fritz Michael Gschwantner <fmg@inspiredminds.at>
 * @copyright Leo Feyer 2005-2014
 * @copyright Tim Gatzky 2013
 * @copyright Fritz Michael Gschwantner 2015
 */
class ArticleModel extends \Model
{

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_article';


	/**
	 * Find an article by its ID or alias and its page
	 *
	 * @param mixed   $varId      The numeric ID or alias name
	 * @param integer $intPid     The page ID
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model|null The model or null if there is no article
	 */
	public static function findByIdOrAliasAndPid($varId, $intPid, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("($t.id=? OR $t.alias=?)");
		$arrValues = array((is_numeric($varId) ? $varId : 0), $varId);

		if ($intPid)
		{
			$arrColumns[] = "$t.pid=?";
			$arrValues[] = $intPid;
		}

		return static::findOneBy($arrColumns, $arrValues, $arrOptions);
	}


	/**
	 * Find a published article by its ID
	 *
	 * @param integer $intId      The article ID
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model|null The model or null if there is no published article
	 */
	public static function findPublishedById($intId, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.id=?");

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		return static::findOneBy($arrColumns, $intId, $arrOptions);
	}


	/**
	 * Find all published articles by their parent ID and column
	 *
	 * @param integer $intPid     The page ID
	 * @param string  $strColumn  The column name
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no articles in the given column
	 */
	public static function findPublishedByPidAndColumn($intPid, $strColumn, array $arrOptions=array())
	{
		// get all the parents
		$arrParent = array( $intPid );
		
		// get the database object
		$objDatabase = \Database::getInstance();

		// search for next parent ids while parent id > 0
		do
		{
			// get the next pid
			$objParent = $objDatabase->prepare("SELECT pid FROM tl_page WHERE id=?")
									 ->limit(1)
									 ->execute($intPid);
	
			// if there are no parents anymore, break the loop
			if( $objParent->numRows < 1 )
				break;
	
			// get the parent id
			$intPid = $objParent->pid;
	
			// store id
			$arrParent[] = $intPid;
	
		}
		while( $intPid );

		// restore current parent id
		$intCurrentPid = $arrParent[0];

		// aggregated collection
		$objCollectionTotal = null;

		// now go through each parent id
		$level = 0;
		foreach( $arrParent as $intPid )
		{
			$t = static::$strTable;
			$arrColumns = array("$t.pid=? AND $t.inColumn=? AND (($t.inherit=1 AND ($t.inheritLevel=0 OR $t.inheritLevel>=?)) OR $t.pid=?)");
			$arrValues = array($intPid, $strColumn, $level, $intCurrentPid);

			if (!BE_USER_LOGGED_IN)
			{
				$time = time();
				$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
			}

			if (!isset($arrOptions['order']))
			{
				$arrOptions['order'] = "$t.sorting";
			}

			$objCollection = static::findBy($arrColumns, $arrValues, $arrOptions);

			if( !is_null( $objCollection ) )
			{
				if( is_null( $objCollectionTotal ) )
					$objCollectionTotal = $objCollection;
				else
					$objCollectionTotal = new \Model\Collection( array_merge( $objCollection->getModels(), $objCollectionTotal->getModels() ), $t );
			}

			// increase level
			++$level;
		}

		// return the combined collection
		return $objCollectionTotal;
	}


	/**
	 * Find all published articles with teaser by their parent ID and column
	 *
	 * @param integer $intPid     The page ID
	 * @param string  $strColumn  The column name
	 * @param array   $arrOptions An optional options array
	 *
	 * @return \Model\Collection|null A collection of models or null if there are no articles in the given column
	 */
	public static function findPublishedWithTeaserByPidAndColumn($intPid, $strColumn, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.pid=? AND $t.inColumn=? AND $t.showTeaser=1");
		$arrValues = array($intPid, $strColumn);

		if (!BE_USER_LOGGED_IN)
		{
			$time = time();
			$arrColumns[] = "($t.start='' OR $t.start<$time) AND ($t.stop='' OR $t.stop>$time) AND $t.published=1";
		}

		if (!isset($arrOptions['order']))
		{
			$arrOptions['order'] = "$t.sorting";
		}

		return static::findBy($arrColumns, $arrValues, $arrOptions);
	}
}
