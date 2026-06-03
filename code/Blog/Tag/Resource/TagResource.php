<?php

namespace Blog\Tag\Resource;

class TagResource extends \App\Resource\Resource
{
    protected string $_table = 'tag';

    public function selectTags(): self
    {
        $this->_query
            ->select(['tag.tag_id', 'tag.code', 'tag.label'])
            ->from($this->_table);
        return $this;
    }

    public function filter(array $tags): self
    {
        if (!empty($tags)) {
            $this->_query->whereIn('tag.code', array_unique($tags));
        }
        return $this;
    }

    public function filterByArticle(int $articleId): self
    {
        $this->_query
            ->leftJoin('article_tag', 'article_tag.tag_id', 'tag.tag_id')
            ->where(['article_tag.article_id = :article_id'], ['article_id' => $articleId])
            ->groupBy(['tag.tag_id']);
        return $this;
    }

    public function filterByCategory(int $categoryId): self
    {
        $this->_query
            ->leftJoin('article_tag', 'article_tag.tag_id', 'tag.tag_id')
            ->leftJoin('category_article', 'category_article.article_id', 'article_tag.article_id')
            ->where(['category_article.category_id = :category_id'], ['category_id' => $categoryId])
            ->groupBy(['tag.tag_id']);
        return $this;
    }
}
