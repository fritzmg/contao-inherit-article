<?php

declare(strict_types=1);

/*
 * This file is part of the InheritArticle Bundle.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

use InheritArticleBundle\EventListener\InheritArticleListener;

$GLOBALS['TL_HOOKS']['getPageLayout'][] = [InheritArticleListener::class, 'onGetPageLayout'];
$GLOBALS['TL_HOOKS']['generatePage'][] = [InheritArticleListener::class, 'onGeneratePage'];
