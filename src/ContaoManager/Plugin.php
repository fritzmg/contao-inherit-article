<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InheritArticleBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use InheritArticleBundle\InheritArticleBundle;

/**
 * Plugin for the Contao Manager.
 */
class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(InheritArticleBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class, 'isotope']),
        ];
    }
}
