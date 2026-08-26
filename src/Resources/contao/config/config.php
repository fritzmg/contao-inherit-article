<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

use InheritArticleBundle\EventListener\InheritArticleListener;

$GLOBALS['TL_HOOKS']['getArticles'][] = [InheritArticleListener::class, 'onGetArticles'];
