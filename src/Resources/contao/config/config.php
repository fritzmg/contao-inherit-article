<?php

declare(strict_types=1);

/*
 * This file is part of the InheritArticle Bundle.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

$GLOBALS['TL_HOOKS']['getPageLayout'][] = ['contao_inherit_article.listener', 'onGetPageLayout'];
$GLOBALS['TL_HOOKS']['generatePage'][] = ['contao_inherit_article.listener', 'onGeneratePage'];
