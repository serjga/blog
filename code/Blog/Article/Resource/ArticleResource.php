<?php

namespace Blog\Article\Resource;

class ArticleResource extends \App\Resource\Resource
{
    protected string $_table = 'article';

    public function selectArticles(): self
    {
        $this->_query
            ->select(['article.article_id', 'article.title', 'article.description', 'article.views', 'article.created_at'])
            ->from($this->_table)
            ->groupBy(['article.created_at', 'article.article_id']);
        return $this;
    }

    public function selectArticle(): self
    {
        $this->_query
            ->select(
                ['article.article_id', 'article.title', 'article.description', 'article.content', 'article.views', 'article.created_at']
            )->from($this->_table);
        return $this;
    }

    public function sortArticles(?string $sortBy = null, string $order = 'ASC'): self
    {
        if ($sortBy) {
            $this->_query->sortBy($sortBy, $order);
        }
        return $this;
    }

    public function filterArticlesByYear(int $year): static
    {
        if ($year) {
            $this->_query->where(['YEAR(created_at) = :year'], ['year' => $year]);
        }
        return $this;
    }

    public function filterArticlesByCategory(int $categoryId): static
    {
        if ($categoryId) {
            $this->_query->where(['category_article.category_id = :category_id'], ['category_id' => $categoryId]);
        }
        return $this;
    }

    public function filterArticlesByIds(array $articleIds): self
    {
        if ($articleIds) {
            $articleIdsStr = implode(',', array_unique($articleIds));
            $this->_query->where(["article.article_id IN ($articleIdsStr)"]);
        }
        return $this;
    }

    public function onlyIncludedCategories (array $categoriesIds): self
    {
        $categoriesIdsStr = implode(',', array_unique($categoriesIds));
        $this->_query->leftJoin('category_article', 'category_article.article_id', 'article.article_id')
            ->where(["category_article.category_id IN ($categoriesIdsStr)"]);
        return $this;
    }

    public function withCategories(): self
    {
        $this->_query->addColumns(["GROUP_CONCAT(category_article.category_id SEPARATOR ',') AS category_ids"])
            ->leftJoin('category_article', 'category_article.article_id', 'article.article_id')
            ->groupBy(['article.article_id', 'article.title', 'article.created_at']);

        return $this;
    }

    public function withImages(): self
    {
        $this->_query->addColumns(["GROUP_CONCAT(article_image.image_id SEPARATOR ',') AS image_ids"])
            ->leftJoin('article_image', 'article_image.article_id', 'article.article_id')
            ->groupBy(['article.article_id', 'article_image.image_id']);
        return $this;
    }

    public function withMainImage(): self
    {
        $this->_query->addColumns(['image.path AS main_image_path'])
            ->leftJoin('article_image', 'article_image.article_id', 'article.article_id')
            ->leftJoin('image', 'image.image_id', 'article_image.image_id')
//            ->where(['article_image.is_main = 1'])
            ->groupBy(['main_image_path']);
        return $this;
    }

    public function selectRecommendedArticles(int $articleId): self
    {
        $this->_query->select(['article.article_id', 'article.title', 'article.description','article.views', 'article.created_at'])
            ->from($this->_table)
            ->leftJoin('recommended_articles', 'recommended_articles.recommended_article_id', 'article.article_id')
            ->where(['recommended_articles.article_id = :article_id'], ['article_id' => $articleId])
            ->sortBy('recommended_articles.sort_order', 'ASC');
        return $this;
    }

    public function selectRelatedArticles(int $articleId): self
    {
        $this->_query->select(['article.article_id', 'article.title', 'article.description','article.views', 'article.created_at'])
            ->from($this->_table)
            ->leftJoin('article_tag', 'article_tag.article_id', 'article.article_id')
            ->rand();
        return $this;
    }

    public function filterArticlesByTags(array $tagIds): self
    {
        $tagIdsStr = implode(',', array_unique($tagIds));
        $this->_query->where(["article_tag.tag_id IN ($tagIdsStr)"]);
        return $this;
    }

    public function countArticles(): self
    {
        $this->_query->select()->from($this->_table)->count('DISTINCT article.article_id');
        return $this;
    }

    public function selectArticlePublishYears(): self
    {
        $this->_query->select(['DISTINCT YEAR(article.created_at) AS year'])
            ->from($this->_table)
            ->sortBy('year', 'DESC');
        return $this;
    }

    public function selectArticleImages(array $articleIds): self
    {
        $articleIdsStr = implode(', ' , $articleIds);
        $this->_query->select(['article_image.article_id', 'image.path AS image_path'])
            ->from('image')
            ->leftJoin('article_image', 'article_image.image_id', 'image.image_id')
            ->where(["article_image.article_id IN ($articleIdsStr)"]);
        return $this;
    }

    public function findArticleIds(string $searchTerm, ?string $sortBy = null, ?string $order = null): self
    {
        $this->_query->select(['DISTINCT article.article_id', 'article.created_at', 'article.views'])
            ->from($this->_table);
        if (!empty($sortBy)) {
            $this->_query->sortBy($sortBy, $order);
        }

        $this->_query->where(
            ['article.title LIKE :search OR article.description LIKE :search OR article.content LIKE :search'],
            ['search' => "%" . $searchTerm . "%"]
        );

        return $this;
    }

    public function updateArticleViews(int $articleId): void
    {
        $this->_query
            ->update($this->_table, ['views = views + 1'])
            ->where(['article_id = :article_id'], ['article_id' => $articleId]);
        $this->query();
    }

    public function withTags(): self
    {
        $this->_query->addColumns(["GROUP_CONCAT(article_tag.tag_id SEPARATOR ',') AS tag_ids"])
            ->leftJoin('article_tag', 'article_tag.article_id', 'article.article_id');
        return $this;
    }
}
