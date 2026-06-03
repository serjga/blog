<?php

namespace Blog\Category\Resource;

class CategoryResource extends \App\Resource\Resource
{
    protected string $_table = 'category';

    public function selectCategory(int $categoryId): self
    {
        $this->_query
            ->select(['category.category_id', 'category.name', 'category.description'])
            ->from($this->_table)
            ->where(['category.category_id = :category_id'], ['category_id' => $categoryId])
            ->groupBy(['category.category_id']);
        return $this;
    }

    public function selectCategories(): self
    {
        $this->_query
            ->select(['category.category_id', 'category.name', 'category.description', 'category.details'])
            ->from($this->_table)
            ->groupBy(['category.category_id']);
        return $this;
    }

    public function sort(): self
    {
        $this->_query->sortBy('category.sort_order', 'ASC')
            ->groupBy(['category.sort_order']);
        return $this;
    }

    public function withArticlesCount(): self
    {
        $this->_query
            ->addColumns(['IFNULL(COUNT(category_article.article_id), 0) AS article_count'])
            ->leftJoin('category_article', 'category_article.category_id', 'category.category_id');
        return $this;
    }

    public function hasArticles(): self
    {
        $this->withArticlesCount();
        $this->_query->having(['article_count > 0']);
        return $this;
    }

    public function withArticlesViewsCount(): self
    {
        $this->_query
            ->addColumns(['SUM(article.views) AS total_views'])
            ->leftJoin('category_article', 'category_article.category_id', 'category.category_id')
            ->leftJoin('article', 'article.article_id', 'category_article.article_id')
            ->sortBy('total_views', 'DESC');
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
}
