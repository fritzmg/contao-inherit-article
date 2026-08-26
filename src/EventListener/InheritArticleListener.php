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

use Contao\ArticleModel;
use Contao\Controller;
use Doctrine\DBAL\Connection;

class InheritArticleListener
{
    private bool $isGetArticlesHook = false;

    public function __construct(private readonly Connection $db)
    {
    }

    public function onGetArticles(int $pageId, string $column): string|null
    {
        // Recursion
        if ($this->isGetArticlesHook) {
            return null;
        }

        $this->isGetArticlesHook = true;

        $articles = $this->getRenderedInheritedArticles($pageId, $column);

        $this->isGetArticlesHook = false;

        return $articles;
    }

    private function getRenderedInheritedArticles(int $pageId, string $column): string
    {
        $baseArticles = Controller::getFrontendModule(0, $column);

        // Initialize pid
        $pid = $pageId;

        // Get all the parents
        $parents = [];

        // Search for next parent ids while parent id > 0
        do {
            // Get the next pid
            $parent = $this->db->fetchAssociative('SELECT pid FROM tl_page WHERE id=?', [$pid]);

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
        $renderedArticles[0] = $baseArticles;

        // Go through each parent
        $level = 1;

        foreach ($parents as $pid) {
            foreach ($this->getInheritedArticles($pid, $column, $level) as $priority => $article) {
                $renderedArticles[$priority] = $article.($renderedArticles[$priority] ?? '');
            }

            // Increase level
            ++$level;
        }

        // Sort by key
        krsort($renderedArticles);

        // Return combined articles
        return implode('', $renderedArticles);
    }

    private function getInheritedArticles($pid, string $column, int $level): array
    {
        $t = ArticleModel::getTable();
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

        foreach (ArticleModel::findBy($columns, $values, $options) ?? [] as $article) {
            $published = $article->published;

            if (!$article->published && $article->inheritUnpublished) {
                $article->published = true;
            }

            if (!isset($renderedArticles[$article->inheritPriority])) {
                $renderedArticles[$article->inheritPriority] = '';
            }

            $renderedArticles[$article->inheritPriority] .= Controller::getArticle($article, false, false, $column);

            $article->published = $published;
        }

        return $renderedArticles;
    }
}
