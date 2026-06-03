<?php

namespace Blog\Article\DataProvider;

use App\Request\Url;
use App\DataProvider\DataProvider;
use App\DataProvider\DataProviderInterface;
use Blog\Article\Resource\ArticleResource;

class ArticleDataProvider extends DataProvider
{
    const string CACHE_ID = 'ART_CID';
    const string RELATED_ARTICLES_CACHE_ID = 'RELATED_ART_CID';
    const string RECOMMENDED_ARTICLES_CACHE_ID = 'RECOMMENDED_ART_CID';

    protected Url $_url;
    public function __construct (?array $data = null)
    {
        $this->_url = new Url();
        parent::__construct($data);
    }

    protected static ?ArticleDataProvider $_instance = null;

    public static function getInstance(): self
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __clone() {}

    public function __wakeup() {
        throw new \Exception("Cannot unserialize singleton");
    }

    public function getArticle(int $articleId): ?DataProviderInterface
    {
        $articles = $this->getData('articles') ?? [];
        if (!isset($articles[$articleId])) {
            $article = $this->_loadArticle($articleId);
            if ($article) {
                $articleData = $this->_getPreparedArticleData($article);
            } else {
                $articleData = null;
            }
            
            $articles[$articleId] = $articleData;
            $this->setData('articles', $articles);
        }
        return $articles[$articleId];
    }

    public function getRelatedArticles(int $articleId, array $excludedArticleIds = []): array
    {
        $relatedArticles = $this->getData('relatedArticles') ?? [];
        if (!isset($relatedArticles[$articleId])) {
            $article = $this->getArticle($articleId);
            if ($article) {
                $loadedArticles = $this->_loadRelatedArticles($article->getData('tags'), $articleId);
                $relatedArticles[$articleId] = [];

                foreach ($loadedArticles as $loadedArticle) {
                    $relatedArticles[$articleId][$loadedArticle->article_id] = $this->_getPreparedArticleData($loadedArticle);
                }
            } else {
                $relatedArticles[$articleId] = [];
            }
            $this->setData('relatedArticles', $relatedArticles);
        }

        $result = [];
        foreach ($relatedArticles[$articleId] as $articleId => $relatedArticle) {
            if (!in_array($articleId, $excludedArticleIds)) {
                $result[] = $relatedArticle;
            }
        }
        return $result;
    }

    protected function _loadRelatedArticles(array $tags, string $articleId): array
    {
        if ($tags && $articleId) {
            $articleResource = new ArticleResource();
            return $articleResource->selectArticles()
                ->filterByTags($tags)
                ->excludeByArticleIds([$articleId])
                ->withMainImage()
                ->query()
                ->all();
        }
        return [];
    }

    protected function _loadArticle($articleId): ?object
    {
        $articleResource = new ArticleResource();
        return $articleResource->selectArticle()
            ->filterArticlesByIds([$articleId])
            ->withCategories()
            ->withMainImage()
            ->withTags()
            ->query()
            ->one();
    }

    protected function _getPreparedArticleData(object $article): DataProvider
    {
        $content = '';
        if (!empty($article->content)) {
            $content = preg_replace('/\s+/', ' ', $article->content);
            $content = '<p>' . str_replace("\n", "</p><p>", $content) . '</p>';
        }

        $articleDataProvider = new DataProvider();
        $articleData = [
            'article_id' => $article->article_id,
            'title' => $article->title,
            'description' => $article->description,
            'content' => $content,
            'created_at' => $article->created_at,
            'views' => $article->views,
            'category_ids' => ($article->category_ids ?? '') ? explode(',', $article->category_ids) : [],
            'image' => $article->main_image_path,
            'tags' => ($article->tags ?? '') ? explode(',', $article->tags) : []
        ];


        $articleDataProvider->initData($articleData);
        return $articleDataProvider;
    }

    public function getRecommendedArticles(int $articleId): array
    {
        $recommendedArticles = $this->getData('recommendedArticles') ?? [];
        if (!isset($recommendedArticles[$articleId])) {
            $loadedArticles = $this->_loadRecommendedArticles($articleId);

            $articles = [];
            foreach ($loadedArticles as $recommendedArticle) {
                $articles[] = $this->_getPreparedArticleData($recommendedArticle);
            }
            $recommendedArticles[$articleId] = $articles;
            $this->setData('recommendedArticles', $recommendedArticles);
        }

        return $recommendedArticles[$articleId];
    }

    protected function _loadRecommendedArticles(int $articleId): array
    {
        $articleResource = new ArticleResource();
        return $articleResource->selectRecommendedArticles($articleId)
            ->withCategories()
            ->withMainImage()
            ->query()
            ->all();
    }
}
