<?php

declare(strict_types=1);

/*
 * This file is part of the InheritArticle Bundle.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

/*
 * Add palettes to tl_article.
 */
$GLOBALS['TL_DCA']['tl_article']['palettes']['default'] = str_replace('{publish_legend', '{inherit_legend:hide},inherit;{publish_legend', $GLOBALS['TL_DCA']['tl_article']['palettes']['default']);
$GLOBALS['TL_DCA']['tl_article']['palettes']['__selector__'][] = 'inherit';
$GLOBALS['TL_DCA']['tl_article']['subpalettes']['inherit'] = 'inheritLevel,inheritPriority,inheritUnpublished';

/*
 * Add fields to tl_article
 */
$GLOBALS['TL_DCA']['tl_article']['fields']['inherit'] = [
    'exclude' => true,
    'label' => &$GLOBALS['TL_LANG']['tl_article']['inherit'],
    'inputType' => 'checkbox',
    'eval' => ['submitOnChange' => true],
    'sql' => "char(1) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_article']['fields']['inheritLevel'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_article']['inheritLevel'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => ['maxlength' => 5, 'rgxp' => 'digit', 'tl_class' => 'w50'],
    'sql' => "smallint(5) unsigned NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_article']['fields']['inheritPriority'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_article']['inheritPriority'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => ['maxlength' => 5, 'rgxp' => 'digit', 'tl_class' => 'w50'],
    'sql' => "smallint(5) NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_article']['fields']['inheritUnpublished'] = [
    'exclude' => true,
    'label' => &$GLOBALS['TL_LANG']['tl_article']['inheritUnpublished'],
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50'],
    'sql' => "char(1) NOT NULL default ''",
];
