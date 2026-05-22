<?php

namespace Blog\Category\Controllers;

use App\Controller\Controller;
use App\Template\View;
use Blog\Article\Resource\ArticleResource;
use Blog\Category\Resource\CategoryResource;
use Blog\Article\Resource\ResourceFactory\ArticleResourceFactory;
use Blog\Category\Resource\ResourceFactory\CategoryResourceFactory;
use App\Request\Url;
use Blog\Tag\Resource\ResourceFactory\TagResourceFactory;
use Blog\Tag\Resource\TagResource;

class CategoryController extends Controller
{
    const int TAGS_LIMIT = 10;
    const int ARTICLE_LIMIT = 3;
    protected CategoryResourceFactory $_categoryResourceFactory;
    protected ArticleResourceFactory $_articleResourceFactory;
    protected TagResourceFactory $_tagResourceFactory;
    protected Url $_url;
    protected View $_view;

    function __construct(
        ArticleResourceFactory $articleResourceFactory,
        CategoryResourceFactory $categoryResourceFactory,
        TagResourceFactory $tagResourceFactory,
        Url $url,
        View $view
    ) {
        parent::__construct();

        $this->_articleResourceFactory = $articleResourceFactory;
        $this->_categoryResourceFactory = $categoryResourceFactory;
        $this->_tagResourceFactory = $tagResourceFactory;
        $this->_url = $url;
        $this->_view = $view;
    }

    public function categories(): void
    {
        $data = [];
        $request = $this->getRequest();
        $requestParams = $request->get();

        $this->_preparePageParams($requestParams, $data);
        $this->_prepareCategoryParams($requestParams, $data);
        $this->_prepareSortParams($requestParams,$data);
        $this->_prepareSearchParams($requestParams, $data);
        $this->_prepareYearParams($requestParams,$data);
        $this->_prepareCategoriesData($data);

        $this->_view->create()
            ->addTemplateVariable('selectedSort', $data['sort'])
            ->addTemplateVariable('sortOptions', $data['sortOptions'])
            ->addTemplateVariable('selectedYear', $data['year'])
            ->addTemplateVariable('yearOptions', $data['yearOptions'])
            ->addTemplateVariable('tagList', $data['tagList'])
            ->addTemplateVariable('articles', $data['articles'])
            ->addTemplateVariable('categoryList', $data['categoryList'])
            ->addTemplateVariable('currentCategory', $data['currentCategory'])
            ->addTemplateVariable('currentPage', $data['page'])
            ->addTemplateVariable('totalPages', $data['totalPages'])
            ->addTemplateVariable('search', $data['search'] ?? '')
            ->addTemplateVariable('searchResult', $data['searchResult'] ?? null)
            ->render('category/index.tpl');
    }

    protected function _prepareSortParams($requestParams, & $data): void
    {
        $data['sort'] = !empty($requestParams['sort']) ? (int) $requestParams['sort'] : null;
        $data['sortBy'] = 'article.created_at';
        $data['order'] = 'DESC';

        $data['sortOptions'] = [
            -1 => 'From Most Recent', 1 => 'To Most Recent',
            -2 => 'From Most Popular', 2 => 'To Most Popular',
        ];

        if (is_int($data['sort']) && isset($data['sortOptions'][$data['sort']])) {
            $data['sortBy'] = (abs($data['sort']) === 2) ? 'article.views' : 'article.created_at';
            $data['order'] = ($data['sort'] < 0) ? 'DESC' : 'ASC';
        }
    }

    protected function _prepareSearchParams($requestParams, & $data): void
    {
        if (isset($requestParams['search'])) {
            $data['search'] = (string) $requestParams['search'];

            /** @var ArticleResource $articleResource */
            $articleResource = $this->_articleResourceFactory->create();

            $articleIds = $articleResource->findArticleIds(
                $data['search'],
                $data['sortBy'] ?? null,
                $data['order'] ?? null
            )
                ->query()
                ->columnValues();

            if (!empty($articleIds)) {
                $data['searchArticleIds'] = $articleIds;
            }
        }
    }

    protected function _prepareCategoryParams($requestParams, & $data): void
    {
        $data['category_id'] = !empty($requestParams['category']) ? (int) $requestParams['category'] : null;
    }

    protected function _prepareYearParams($requestParams, & $data): void
    {
        $data['year'] = !empty($requestParams['year']) ? (int) $requestParams['year'] : null;

        /** @var ArticleResource $articleResource */
        $articleResource = $this->_articleResourceFactory->create();
        $years = $articleResource->selectArticlePublishYears()
            ->query()
            ->columnValues();

        $yearOptions = [];
        foreach ($years as $year) {
            $yearOptions[$year] = $year;
        }
        $data['yearOptions'] = $yearOptions;
    }

    protected function _preparePageParams($requestParams, & $data): void
    {
        $data['page'] = !empty($requestParams['page']) ? (int) $requestParams['page'] : 1;
    }

    protected function _prepareCategoriesData(& $data): void
    {
        /** @var CategoryResource $categoryResource */
        $categoryResource = $this->_categoryResourceFactory->create();
        $categories = $categoryResource->selectCategories()
            ->withTagIds()
            ->query()
            ->all();

        $tagIds = [];
        $categoryList = [];
        $categoryIdToCategoryNameMap = [];
        $data['currentCategory'] = null;
        foreach ($categories as $category) {
            $categoryData = [
                'id' => $category->category_id,
                'name' => $category->name,
                'description' => $category->description,
                'articleCount' => $category->article_count,
            ];
            $categoryIdToCategoryNameMap[$category->category_id] = $category->name;
            $categoryList[] = $categoryData;

            if ($data['category_id'] === $category->category_id) {
                $data['currentCategory'] = $categoryData;
            }

            if ($category->tag_ids) {
                $tagIds = array_merge($tagIds, explode(',', (int) $category->tag_ids));
            }
        }
        $data['categoryMap'] = $categoryIdToCategoryNameMap;
        $data['categoryList'] = $categoryList;

        $this->_prepareArticlesData(self::ARTICLE_LIMIT, $data);
        $this->_prepareTagsData($tagIds, $data);
    }

    protected function _prepareArticlesData($limit, & $data): void
    {
        /** @var ArticleResource $articleResource */
        $articleResource = $this->_articleResourceFactory->create();
        $articles = $articleResource->selectArticles()
            ->filterArticlesByYear((int) $data['year'])
            ->filterArticlesByCategory((int) $data['category_id'])
            ->sortArticles((string) $data['sortBy'], $data['order'] ?? 'DESC')
            ->withMainImage()
            ->withCategories()
            ->filterArticlesByIds($data['searchArticleIds'] ?? [])
            ->page($data['page'], $limit)
            ->query()
            ->all();

        $articleList = [];
        foreach ($articles as $article) {
            $articleData = [
                'id' => $article->article_id,
                'title' => $article->title,
                'description' => $article->description,
                'views' => $article->views,
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

        $data['articles'] = $articleList;

        $this->_preparePaginationData($limit, $data);
    }



//    protected function _prepareArticleImages(array $articleIds, & $data): void
//    {
//        $articleImageMap = [];
//        if ($articleIds) {
//            $selectImages = $this->_articleResource->selectArticleImages($articleIds);
//            $articleImagesQuery = $this->_articleResource->query($selectImages);
//            $articleImages = $this->_articleResource->fetchCollection($articleImagesQuery);
//
//            foreach ($articleImages as $image) {
//                $articleImageMap[$image->article_id] = $image->image_path;
//            }
//        }
//
//        $url = (new Url());
//        foreach ($data['articles'] as & $article) {
//            $article['imageUrl'] =  $url->getImageUrl(['path' => $articleImageMap[$article['id']] ?? '']);
//        }
//    }

    protected function _preparePaginationData($limit, & $data): void
    {
        /** @var ArticleResource $articleResource */
        $articleResource = $this->_articleResourceFactory->create();
        $articleResource->countArticles()
            ->filterArticlesByYear((int) $data['year'])
            ->filterArticlesByIds( $data['searchArticleIds'] ?? []);

        if (!empty($data['category_id'])) {
            $articleResource->onlyIncludedCategories([$data['category_id']]);
        }

        $totalRecords = $articleResource->query()->count();

        $data['articleTotalRecords'] = $totalRecords;

        $data['totalPages'] = ceil($data['articleTotalRecords'] / $limit);

        if (isset($data['search'])) {
            $data['searchResult'] = [
                'totalRecords' => $data['articleTotalRecords'],
                'count' => count($data['articles'])
            ];
        }
    }

    protected function _prepareTagsData(array $tagIds, array & $data): void
    {
        if ($tagIds) {
            /** @var TagResource $tagResource */
            $tagResource = $this->_tagResourceFactory->create();
            $tagResource->selectTags();

            if ($data['currentCategory']) {
                $tagResource->filterTags(array_unique($tagIds))
                    ->page(1, self::TAGS_LIMIT);
            }

            $tags = $tagResource->query()->all();

            $tagList = [];
            foreach ($tags as $tag) {
                $tagList[] = [
                    'id' => $tag->tag_id,
                    'label' => $tag->label,
                ];
            }

            $data['tagList'] = $tagList;
        }
    }

}
