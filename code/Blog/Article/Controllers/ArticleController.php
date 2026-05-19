<?php

namespace Blog\Article\Controllers;

use App\Request\Url;
use App\Session\Session;
use App\Template\View;
use App\Cookie\Cookie;
use Blog\Article\Resource\ArticleResource;
use Blog\Category\Resource\CategoryResource;
use Blog\Article\Resource\ResourceFactory\ArticleResourceFactory;
use Blog\Category\Resource\ResourceFactory\CategoryResourceFactory;

class ArticleController extends \App\Controller\Controller
{
    const int RELATED_ARTICLES_LIMIT = 3;
    protected CategoryResourceFactory $_categoryResourceFactory;
    protected ArticleResourceFactory $_articleResourceFactory;
    protected Url $_url;
    protected View $_view;

    function __construct(
        ArticleResourceFactory $articleResourceFactory,
        CategoryResourceFactory $categoryResourceFactory,
        Url $url,
        View $view
    ) {
        parent::__construct();
        $this->_articleResourceFactory = $articleResourceFactory;
        $this->_categoryResourceFactory = $categoryResourceFactory;
        $this->_url = $url;
        $this->_view = $view;
    }

    public function article(int $id): void
    {
        if (empty($id)) {
            // throw Exception invalid id
        }

        print_r($id);

        $data = [];

        $data['article_id'] = $id;

        $this->_prepareCategoriesData($data);
        $this->_prepareArticleData($id, $data);
        $this->_updateArticleViews($id);

        $this->_view
            ->create()
            ->addTemplateVariable('article', $data['article'] ?? null)
            ->addTemplateVariable('recommendedArticles', array_values($data['recommendedArticles']))
            ->addTemplateVariable('relatedArticles', array_values($data['relatedArticles']))
            ->addTemplateVariable('categoryList', $data['categoryList'] ?? '')
            ->render('Article/index.tpl');
    }

    protected function _prepareCategoriesData(& $data): void
    {
        /** @var CategoryResource $categoryResource */
        $categoryResource = $this->_categoryResourceFactory->create();
        $categories = $categoryResource->selectCategories()->query()->all();

        $categoryList = [];
        $categoryIdToCategoryNameMap = [];
        foreach ($categories as $category) {
            $categoryData = [
                'id' => $category->category_id,
                'name' => $category->name,
                'articleCount' => $category->article_count,
            ];
            $categoryIdToCategoryNameMap[$category->category_id] = $category->name;
            $categoryList[] = $categoryData;
        }
        $data['categoryMap'] = $categoryIdToCategoryNameMap;
        $data['categoryList'] = $categoryList;
    }


    protected function _prepareArticleData(int $id, & $data): void
    {
        /** @var ArticleResource $articleResource */
        $articleResource = $this->_articleResourceFactory->create();
        $article = $articleResource->selectArticle()
            ->filterArticlesByIds([$id])
            ->withCategories()
            ->withMainImage()
            ->withTags()
            ->query()
            ->one();

        if (!$article) {
            // throw Exception Article not found
        }

//        $article = $articles[0];

        $data['article'] = [
            'id' => $article->article_id,
            'title' => $article->title,
            'content' => $article->content,
            'date' => $article->created_at,
            'views' => $article->views,
            'categories' => [],
            'image' => $article->main_image_path ? $this->_url->getImageUrl(['path' => $article->main_image_path]) : null
        ];

        $data['tag_ids'] = $article->tag_ids ? explode(',', $article->tag_ids) : [];
        $data['article']['categories'] = $this->_getCategoriesMap($article->category_ids, $data);
        $data['recommendedArticles'] = $this->_getRecommendedArticles ($id, $data);
        $data['relatedArticles'] = $this->_getRelatedArticles ($data['tag_ids'], $data);
    }

    protected function _getRecommendedArticles (int $articleId, & $data): array
    {
        $recommendedArticles = [];
        if ($articleId) {
            /** @var ArticleResource $articleResource */
            $articleResource = $this->_articleResourceFactory->create();
            $articles = $articleResource->selectRecommendedArticles($articleId)
                ->withCategories()
                ->withMainImage()
                ->query()
                ->all();

            if (count($articles)) {
                foreach ($articles as $article) {
                    $articleData = [
                        'id' => $article->article_id,
                        'title' => $article->title,
                        'description' => $article->description,
                        'createdAt' => $article->created_at,
                        'views' => $article->views,
                        'categories' => $this->_getCategoriesMap($article->category_ids, $data),
                        'image' => $article->main_image_path ? $this->_url->getImageUrl(['path' => $article->main_image_path]) : null
                    ];

                    $recommendedArticles[$article->article_id] = $articleData;
                }
            }
        }
        return $recommendedArticles;
    }

    protected function _getCategoriesMap(?string $categoryIdsStr, array $data): array
    {
        $categoriesMap = [];
        if (!empty($data['categoryMap']) && is_string($categoryIdsStr)) {
            $categoryIds = explode(',', $categoryIdsStr);

            foreach ($categoryIds as $categoryId) {
                if (isset($data['categoryMap'][$categoryId])) {
                    $categoriesMap[$categoryId] = $data['categoryMap'][$categoryId];
                }
            }
        }
        return $categoriesMap;
    }

    protected function _getRelatedArticles(array $tagIds, & $data): array
    {
        $relatedArticles = [];
        if ($tagIds) {
            /** @var ArticleResource $articleResource */
            $articleResource = $this->_articleResourceFactory->create();
            $articles = $articleResource->selectArticles()
                ->filterArticlesByTags($tagIds)
                ->withCategories()
                ->withMainImage()
                ->withTags()
                ->page(1, self::RELATED_ARTICLES_LIMIT)
                ->query()
                ->all();

            foreach ($articles as $article) {
                $articleData = [
                    'id' => $article->article_id,
                    'title' => $article->title,
                    'image' => $article->main_image_path ? $this->_url->getImageUrl(['path' => $article->main_image_path]) : null
                ];

                $relatedArticles[$article->article_id] = $articleData;
            }
        }

        return $relatedArticles;
    }

    protected function _updateArticleViews(int $articleId): void
    {
        $cookieName = "BlogArtVis-$articleId";
        if (!Cookie::has($cookieName, 1)) {
            /** @var ArticleResource $articleResource */
            $articleResource = $this->_articleResourceFactory->create();
            $articleResource->updateArticleViews($articleId);
            Session::setTemporary('need_update_article_views_cookie', $cookieName);
            $this->_request->reload();
        }
    }
}
