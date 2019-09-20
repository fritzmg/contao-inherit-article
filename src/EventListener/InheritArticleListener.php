<?php

declare(strict_types=1);

/*
 * This file is part of the InheritArticle Bundle.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InheritArticleBundle\EventListener;

use Contao\CoreBundle\Framework\FrameworkAwareInterface;
use Contao\CoreBundle\Framework\FrameworkAwareTrait;
use Contao\LayoutModel;
use Contao\PageModel;
use Contao\PageRegular;
use Doctrine\DBAL\Connection;

class InheritArticleListener implements FrameworkAwareInterface
{
    use FrameworkAwareTrait;

    protected $columns;
    protected $sections;
    protected $modules;
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function onGetPageLayout(PageModel $pageModel, LayoutModel $layoutModel, PageRegular $pageRegular): void
    {
        $this->framework->initialize();
        $stringUtil = $this->framework->getAdapter(\Contao\StringUtil::class);
        $moduleModel = $this->framework->getAdapter(\Contao\ModuleModel::class);

        // Reset the cached data
        $this->columns = [];
        $this->sections = [];
        $this->modules = serialize([]);

        // Initialize modules and sections
        $arrSections = ['header', 'left', 'right', 'main', 'footer'];
        $arrModules = $stringUtil->deserialize($layoutModel->modules);
        $arrModuleIds = [];

        // Filter the disabled modules
        foreach ($arrModules as $module) {
            if ($module['enable']) {
                $arrModuleIds[] = $module['mod'];
            }
        }

        // Get all modules in a single DB query
        $objModules = $moduleModel->findMultipleByIds($arrModuleIds);

        if (null !== $objModules || 0 === $arrModules[0]['mod'] || '0' === $arrModules[0]['mod']) { // see #4137
            $arrMapper = [];

            // Create a mapper array in case a module is included more than once (see #4849)
            if (null !== $objModules) {
                while ($objModules->next()) {
                    $arrMapper[$objModules->id] = $objModules->current();
                }
            }

            foreach ($arrModules as $arrModule) {
                // Disabled module
                if (!$arrModule['enable']) {
                    continue;
                }

                // Replace the module ID with the module model
                if ($arrModule['mod'] > 0 && isset($arrMapper[$arrModule['mod']])) {
                    $arrModule['mod'] = $arrMapper[$arrModule['mod']];
                }

                // Generate the modules
                if (\in_array($arrModule['col'], $arrSections, true)) {
                    // Filter active sections (see #3273)
                    if ('header' === $arrModule['col'] && '2rwh' !== $layoutModel->rows && '3rw' !== $layoutModel->rows) {
                        continue;
                    }
                    if ('left' === $arrModule['col'] && '2cll' !== $layoutModel->cols && '3cl' !== $layoutModel->cols) {
                        continue;
                    }
                    if ('right' === $arrModule['col'] && '2clr' !== $layoutModel->cols && '3cl' !== $layoutModel->cols) {
                        continue;
                    }
                    if ('footer' === $arrModule['col'] && '2rwf' !== $layoutModel->rows && '3rw' !== $layoutModel->rows) {
                        continue;
                    }

                    $this->columns[$arrModule['col']] .= $this->getFrontendModule($pageModel, $arrModule['mod'], $arrModule['col']);
                } else {
                    $this->sections[$arrModule['col']] .= $this->getFrontendModule($pageModel, $arrModule['mod'], $arrModule['col']);
                }
            }
        }

        // Empty the modules in the layout
        $this->modules = $layoutModel->modules;
        $layoutModel->modules = serialize([]);
    }

    public function onGeneratePage(PageModel $pageModel, LayoutModel $layoutModel, PageRegular $pageRegular): void
    {
        foreach ($this->columns as $column => $content) {
            $pageRegular->Template->{$column} = $content;
        }

        $pageRegular->Template->sections = $this->sections;

        $layoutModel->modules = $this->modules;
    }

    protected function getFrontendModule(PageModel $page, $module, string $column): string
    {
        $generatedModule = $this->framework->getAdapter(\Contao\Controller::class)->getFrontendModule($module, $column);

        if (\is_object($module) && (0 !== $module || '0' !== $module)) {
            return $generatedModule;
        }

        // Initialize pid
        $pid = $page->id;

        // Get all the parents
        $parents = [];

        // Search for next parent ids while parent id > 0
        do {
            // Get the next pid
            $parent = $this->db->executeQuery('SELECT pid FROM tl_page WHERE id=?', [$pid])->fetch();

            // If there are no parents anymore, break the loop
            if (!$parent) {
                break;
            }

            // Get the parent id
            $pid = $parent['pid'];

            // Store id
            $parents[] = $pid;
        } while ($pid);

        // Initialize rendered article modules
        $renderedArticles = [];
        $renderedArticles[0] = $generatedModule;

        // Go through each parent
        $level = 1;
        foreach ($parents as $pid) {
            $inheritArticles = $this->getInheritedArticles($pid, $column, $level);

            if (!empty($inheritArticles)) {
                foreach ($inheritArticles as $priority => $article) {
                    $renderedArticles[$priority] = $article.($renderedArticles[$priority] ?? '');
                }
            }

            // Increase level
            ++$level;
        }

        // Sort by key
        krsort($renderedArticles);

        // Return combined articles
        return implode('', $renderedArticles);
    }

    protected function getInheritedArticles($pid, string $column, int $level): array
    {
        // Get adapters
        $controller = $this->framework->getAdapter(\Contao\Controller::class);
        $articleModel = $this->framework->getAdapter(\Contao\ArticleModel::class);

        $t = $articleModel->getTable();
        $columns = [
            "$t.pid = ?",
            "$t.inColumn = ?",
            "$t.inherit = '1'",
            "($t.inheritLevel = '0' OR $t.inheritLevel >= ?)",
        ];

        $values = [
            $pid,
            $column,
            $level,
        ];

        $options = ['order' => "$t.sorting"];

        $renderedArticles = [];

        if (null !== ($articles = $articleModel->findBy($columns, $values, $options))) {
            foreach ($articles as $article) {
                $published = $article->published;

                if (!$article->published && $article->inheritUnpublished) {
                    $article->published = true;
                }

                if (!isset($renderedArticles[$article->inheritPriority])) {
                    $renderedArticles[$article->inheritPriority] = '';
                }

                $renderedArticles[$article->inheritPriority] .= $controller->getArticle($article, false, false, $column);

                $article->published = $published;
            }
        }

        return $renderedArticles;
    }
}
