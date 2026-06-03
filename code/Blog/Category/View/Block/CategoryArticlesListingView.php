<?php
namespace Blog\Category\View\Block;

use App\Request\Url;
use App\View\BlockView;
use App\DataProvider\DataProvider;
use Blog\Category\Registry;
use Blog\Category\DataProvider\CategoryDataProvider;
use Blog\Article\Resource\ArticleResource;
use Blog\Base\View\Block\PaginationView;

class CategoryArticlesListingView extends BlockView
{
    protected string $_template = 'blocks/block__category_articles_listing';

    public function render(bool $return = false): ?string
    {
        $this->_template = (new Registry())->getTemplatePath($this->_template);

        $queryParams = $this->getInputData('queryParams');
        $selectArticlesCount = $this->_countArticles();
        $this->_appendArticleFilters($selectArticlesCount, $queryParams);
        $paginationData = $this->_getPaginationData(
            $selectArticlesCount,
            (int) $queryParams->getData('page'),
            (int) $queryParams->getData('limit')
        );

        $selectArticles = $this->_selectArticles();
        $this->_appendArticleFilters($selectArticles, $queryParams);
        $articles = $this->_loadArticles($selectArticles, $queryParams, $paginationData['currentPage']);

        $categoryMap = (new CategoryDataProvider())->getCategoryMap();
        $articlesData = $this->_prepareArticlesData($articles, $categoryMap);

        $searchResult = [
            'total_records' => $paginationData['totalRecords'],
            'count' => count($articlesData)
        ];

        $this->addTemplateVariable('search', $queryParams->getData('search'))
            ->addTemplateVariable('search_result', $searchResult)
            ->addTemplateVariable('articles', $articlesData);

        // pagination
        $paginationView = new PaginationView($this->_inputData, $this);
        $paginationView
            ->setData('totalPages', $paginationData['totalPages'])
            ->setData('currentPage', $paginationData['currentPage']);

        $paginationSectionContent = $paginationView->render(true);
        $this->addTemplateVariable('block__pagination', $paginationSectionContent);

        return parent::render($return);
    }

    protected function _selectArticles(): ArticleResource
    {
        $articleResource = new ArticleResource();
        $articleResource->selectArticles()
            ->withMainImage()
            ->withCategories();

        return $articleResource;
    }

    public function _countArticles(): ArticleResource
    {
        $articleResource = new ArticleResource();
        $articleResource->countArticles();
        return $articleResource;
    }

    public function _appendArticleFilters(ArticleResource $articleResource, $queryParams): void
    {
        if ($queryParams->isset('categoryId')) {
            $articleResource->filterByCategory($queryParams->getData('categoryId'));
        }

        if ($queryParams->isset('year')) {
            $articleResource->filterByYear($queryParams->getData('year'));
        }

        if ($queryParams->isset('search')) {
            $articleResource->filterBySearchCondition($queryParams->getData('search'));
        }

        $queryTags = $queryParams->getData('tags');
        $tags = is_string($queryTags) ? explode(',', $queryTags) : [];
        if ($tags) {
            $articleResource->filterByTags($tags);
        }
    }

    protected function _loadArticles(ArticleResource $articleResource, DataProvider $queryParams, int $page): ?array
    {
        $limit = $queryParams->getdata('limit') ?? 10;
        $sort = $queryParams->getdata('sort') === 'popular' ? 'article.views' : 'article.created_at';
        $order = $queryParams->getdata('order') === 'asc' ? 'ASC' : 'DESC';

        return $articleResource
            ->sort($sort, $order)
            ->page($page, $limit)
            ->query()
            ->all();
    }

    protected function _prepareArticlesData(array $articles, array $categoryMap): array
    {
        $url = new Url();
        $articleList = [];
        foreach ($articles as $article) {
            $articleData = [
                'id' => (int) $article->article_id,
                'title' => (string) $article->title,
                'description' => (string) $article->description,
                'views' => (int) $article->views,
                'createdAt' => (string) $article->created_at,
                'image' => $article->main_image_path ? $url->getImageUrl(['path' => $article->main_image_path]) : null,
            ];

            $categoryIds = explode(',', $article->category_ids);
            foreach ($categoryIds as $categoryId) {
                $categoryData = $categoryMap[$categoryId] ?? null;
                if ($categoryData) {
                    $articleData['categories'][$categoryId] = $categoryData->getData('name');
                }
            }
            $articleList[] = $articleData;
        }
        return $articleList;
    }

    protected function _getPaginationData(ArticleResource $articleResource, int $page, int $limit): array
    {
        $totalRecords = $articleResource->query()->count();
        $totalPages = ceil($totalRecords / $limit);

        if ($totalPages < $page) {
            $page = $totalPages;
        } else if ($page < 1) {
            $page = 1;
        }

        return [
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalRecords' => $totalRecords
        ];
    }
}
