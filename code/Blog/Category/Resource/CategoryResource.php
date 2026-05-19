<?php

namespace Blog\Category\Resource;

class CategoryResource extends \App\Resource\Resource
{
    protected string $_table = 'category';

    public function selectCategories(): self
    {
        $this->_query
            ->select(['category.category_id', 'category.name', 'category.description'])
            ->from($this->_table)
            ->addColumns(['IFNULL(COUNT(category_article.article_id), 0) AS article_count'])
            ->leftJoin('category_article', 'category_article.category_id', 'category.category_id')
            ->groupBy(['category.category_id'])
            ->having(['article_count > 0'])
            ->sortBy('category.sort_order', 'ASC')
            ->groupBy(['category.sort_order']);

        return $this;
    }

    public function withArticlesViewsCount(): self
    {
        $this->_query
            ->addColumns(['SUM(article.views) AS total_views'])
            ->leftJoin('category_article', 'category_article.category_id', 'category.category_id');

        return $this;
    }

    public function withArticleIds(): self
    {
        $this->_query
            ->addColumns(["GROUP_CONCAT(article.article_id ORDER BY article.created_at DESC SEPARATOR ',') AS article_ids"])
            ->leftJoin('category_article', 'category_article.category_id', 'category.category_id')
            ->leftJoin('article', 'article.article_id', 'category_article.article_id');
        return $this;
    }

    public function withTagIds(): self
    {
        $this->_query
            ->addColumns(["GROUP_CONCAT(article_tag.tag_id SEPARATOR ',') AS tag_ids"])
            ->leftJoin('category_article', 'category_article.category_id', 'category.category_id')
            ->leftJoin('article_tag', 'article_tag.article_id', 'category_article.article_id');
        return $this;
    }
}
