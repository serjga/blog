<?php

namespace Blog\Category\Controllers;

use App\Controller\Controller;
use App\Request\Url;
use App\Template\View;
use Blog\Article\Resource\ArticleResource;
use Blog\Article\Resource\ResourceFactory\ArticleResourceFactory;
use Blog\Category\Resource\CategoryResource;
use Blog\Category\Resource\ResourceFactory\CategoryResourceFactory;

class HomeController extends Controller
{
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

    public function index(): void
    {
        $data = [];
        $this->_prepareCategoriesData($data);

        $this->_view
            ->create()
            ->addTemplateVariable('categories', $data['categories'])
            ->addTemplateVariable('popularCategories', $data['popularCategories'])
            ->addTemplateVariable('popularArticles', $data['popularArticles'])
            ->addTemplateVariable('categoryList', $data['categoryList'])
            ->render('home/index.tpl');
    }


    protected function _prepareCategoriesData(& $data): void
    {
        /** @var CategoryResource $categoryResource */
        $categoryResource = $this->_categoryResourceFactory->create();
        $categories = $categoryResource->selectCategories()
            ->withArticleIds()
            ->withArticlesViewsCount()
            ->query()
            ->all();

        $categoryList = [];
        $homePageCategories = [];
        $categoryIdToCategoryNameMap = [];
        $categoryToArticlesMap = [];
        $articleToCategoryMap = [];
        $categoryToTotalViewsCountMap = [];
        foreach ($categories as $category) {
            $categoryId = $category->category_id;
            $categoryData = [
                'id' => $categoryId,
                'name' => $category->name,
                'articles' => []
            ];

            $categoryToTotalViewsCountMap[$category->category_id] = $category->total_views;

            $categoryIdToCategoryNameMap[$category->category_id] = $category->name;
            $categoryList[] = $categoryData;
            $categoryArticles = explode(',', (string) $category->article_ids);

            foreach ($categoryArticles as $articleId) {
                // display not repeated articles
                if (!isset($articleToCategoryMap[$articleId])) {
                    $articleToCategoryMap[$articleId] = $categoryId;
                    $categoryToArticlesMap[$categoryId][] = $articleId;
                }

                if (count($categoryToArticlesMap[$categoryId] ?? []) === 3) {
                    break;
                }
            }

            if ($category->article_count) {
                $homePageCategories[] = $categoryData;
            }
        }

        $data['categoryToTotalViewsCountMap'] = $categoryToTotalViewsCountMap;
        $data['categoryToArticlesMap'] = $categoryToArticlesMap;
        $data['articleToCategoryMap'] = $articleToCategoryMap;
        $data['categoryMap'] = $categoryIdToCategoryNameMap;
        $data['categoryList'] = $categoryList;
        $data['categories'] = $homePageCategories;

        $this->_prepareMostPopularCategoriesData($data);
        if ($data['articleToCategoryMap']) {
            $this->_prepareRelatedArticles($data);
        }
        $this->_preparePopularArticlesData($data);
    }

    protected function _prepareMostPopularCategoriesData (& $data): void
    {
        $popularCategories = [];
        if ($data['categoryToTotalViewsCountMap']) {
            $limit = 4;
            arsort($data['categoryToTotalViewsCountMap']);
            $topCategories = array_slice($data['categoryToTotalViewsCountMap'], 0, $limit, true);

            foreach ($topCategories as $categoryId => $views) {
                if (isset($data['categoryMap'][$categoryId])) {
                    $categoryData = [
                        'id' => $categoryId,
                        'name' => $data['categoryMap'][$categoryId],
                        'views' => $views
                    ];

                    $popularCategories[] = $categoryData;
                }
            }
        }

        $data['popularCategories'] = $popularCategories;
    }

    protected function _prepareRelatedArticles(& $data): void
    {
        $articleIds = array_keys($data['articleToCategoryMap']);

        /** @var ArticleResource $articleResource */
        $articleResource = $this->_articleResourceFactory->create();
        $articles = $articleResource->selectArticles()
            ->sortArticles('created_at', 'DESC')
            ->filterArticlesByIds($articleIds)
            ->withMainImage()
            ->query()
            ->all();

        $articlesMap = [];
        foreach ($articles as $article) {
            $articleId = $article->article_id;
            $articleData = [
                'id' => $articleId,
                'title' => $article->title,
                'description' => $article->description,
                'views' => $article->views,
                'createdAt' => $article->created_at,
                'time' => strtotime($article->created_at),
                'image' => $article->main_image_path ? $this->_url->getImageUrl(['path' => $article->main_image_path]) : null,
                'categories' => []
            ];

            $articlesMap[$articleId] = $articleData;
        }

        foreach ($data['categories'] as & $category) {
            $categoryId = $category['id'];
            $categoryArticles = [];
            // article correct order
            if (!empty($data['categoryToArticlesMap'][$categoryId])) {
                $categoryArticleIds = $data['categoryToArticlesMap'][$categoryId];
                foreach ($categoryArticleIds as $articleId) {
                    if (isset($articlesMap[$articleId])) {
                        $categoryArticles[] = $articlesMap[$articleId];
                    }
                }
            }

            $category['articles'] = $categoryArticles;
        }
    }

    protected function _preparePopularArticlesData(& $data): void
    {
        /** @var ArticleResource $articleResource */
        $articleResource = $this->_articleResourceFactory->create();
        $articles = $articleResource->selectArticles()
            ->sortArticles('article.views', 'DESC')
            ->withMainImage()
            ->withCategories()
            ->page(1, 3)
            ->query()
            ->all();

        $articleList = [];
        foreach ($articles as $article) {
            $articleData = [
                'id' => $article->article_id,
                'title' => $article->title,
                'description' => $article->description,
//                'views' => $article->views,
                'createdAt' => $article->created_at,
                'image' => $article->main_image_path ? $this->_url->getImageUrl(['path' => $article->main_image_path]) : null
            ];

            $categoryIds = explode(',', $article->category_ids);
            foreach ($categoryIds as $categoryId) {
                if (isset($data['categoryMap'][$categoryId])) {
                    $articleData['categories'][$categoryId] = $data['categoryMap'][$categoryId];
                }
            }
            $articleList[] = $articleData;
        }

        $data['popularArticles'] = $articleList;
    }
}
