<?php

namespace Blog\Category\Controller;

use App\Request\Url;
use App\Logger\Logger;
use App\Controller\Controller;
use App\Request\RequestFactory;
use App\DataProvider\DataProvider;
use App\DataProvider\DataProviderFactory;
use Blog\Tag\Resource\TagResourceFactory;
use Blog\Category\Resource\CategoryResource;
use Blog\Base\View\Page\NotFoundPageViewFactory;
use Blog\Article\Resource\ArticleResourceFactory;
use Blog\Category\Resource\CategoryResourceFactory;
use Blog\Category\View\Page\CategoryPageViewFactory;
use Blog\Category\DataProvider\CategoryDataProviderFactory;

class CategoryController extends Controller
{
    use Logger;
    const int ARTICLE_LIMIT = 3;
    protected Url $_url;
    protected CategoryDataProviderFactory $_categoryDataProviderFactory;
    protected ArticleResourceFactory $_articleResourceFactory;
    protected TagResourceFactory $_tagResourceFactory;
    protected NotFoundPageViewFactory $_notFoundPageViewFactory;
    protected CategoryPageViewFactory $_categoryPageViewFactory;
    protected DataProviderFactory $_dataProviderFactory;
    private CategoryResourceFactory $_categoryResourceFactory;

    function __construct(
        RequestFactory $requestFactory,
        ArticleResourceFactory $articleResourceFactory,
        CategoryDataProviderFactory $categoryDataProviderFactory,
        TagResourceFactory $tagResourceFactory,
        CategoryPageViewFactory $categoryPageViewFactory,
        DataProviderFactory $dataProviderFactory,
        NotFoundPageViewFactory $notFoundPageViewFactory,
        CategoryResourceFactory $categoryResourceFactory,
        Url $url
    ) {
        parent::__construct($requestFactory);

        $this->_articleResourceFactory = $articleResourceFactory;
        $this->_categoryDataProviderFactory = $categoryDataProviderFactory;
        $this->_tagResourceFactory = $tagResourceFactory;
        $this->_categoryPageViewFactory = $categoryPageViewFactory;
        $this->_dataProviderFactory = $dataProviderFactory;
        $this->_notFoundPageViewFactory = $notFoundPageViewFactory;
        $this->_categoryResourceFactory = $categoryResourceFactory;
        $this->_url = $url;
    }

    public function categories(): void
    {
        try {
            $request = $this->getRequest();
            $requestParams = $request->get();

            $queryParams = $this->_getQueryParams($requestParams, $request);

            $categoryPageData = $this->_dataProviderFactory->create();
            $categoryPageData->setData('queryParams', $queryParams);

            $categoryPageView = $this->_categoryPageViewFactory->create($categoryPageData);
            $categoryPageView->render();
        } catch(\Throwable $e) {
            $this->log($e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $request->withErrorMessage('Something went wrong. Try again later.')->back();
        }
    }

    public function category(int $id): void
    {
        try {
            $request = $this->getRequest();
            $requestParams = $request->get();

            $queryParamsData = $this->_getQueryParams($requestParams, $request);

            /** @var CategoryResource $categoryResource */
            $categoryResource = $this->_categoryResourceFactory->create();
            $category = $categoryResource->selectCategory($id)
                ->query()
                ->one();

            if (!$category) {
                // Page Not Found
                $notFoundPageView = $this->_notFoundPageViewFactory->create($queryParamsData);
                $notFoundPageView->render();
            } else {
                // Category Page
                $categoryPageData = $this->_dataProviderFactory->create();
                $categoryPageData->setData('queryParams', $queryParamsData);
                $categoryPageData->setData('currentCategory', $category);

                $categoryPageView = $this->_categoryPageViewFactory->create($categoryPageData);
                $categoryPageView->render();
            }
        } catch(\Throwable $e) {
            $this->log($e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $request->withErrorMessage('Something went wrong. Try again later.')->back();
        }
    }

    protected function _getQueryParams($requestParams, $request): DataProvider
    {
        $categoryId = $requestParams['id'] ?? null;
        if (!is_null($categoryId) && $categoryId <= 0) {
            $request->withErrorMessage('Invalid request.')->back();
        }

        $availableSortParams = ['date', 'popular'];
        $requestSort = $requestParams['sort'] ?? null;
        if ($requestSort && !in_array($requestSort, $availableSortParams)) {
            $request->withErrorMessage('Invalid request.')->back();
        }

        $availableOrderParams = ['asc', 'desc'];
        $requestOrder = $requestParams['order'] ?? null;
        if ($requestOrder && !in_array($requestOrder, $availableOrderParams)) {
            $request->withErrorMessage('Invalid request.')->back();
        }

        $requestYearFilter = $requestParams['year'] ?? null;
        if ($requestYearFilter && ($requestYearFilter < 2020 || $requestYearFilter > date("Y"))) {
            $request->withErrorMessage('Invalid request.')->back();
        }

        $requestTags = !empty($requestParams['tags']) ? (string) $requestParams['tags'] : [];

        $page = !empty($requestParams['page']) ? (int) $requestParams['page'] : 1;
        if ($page <= 0) {
            $request->withErrorMessage('Invalid request.')->back();
        }

        $search = !empty($requestParams['search']) ? (string) $requestParams['search'] : null;

        $articleLimit = !empty($requestParams['limit']) ? (int) $requestParams['limit'] : self::ARTICLE_LIMIT;
        if ($articleLimit > self::ARTICLE_LIMIT) {
            $articleLimit = self::ARTICLE_LIMIT;
        }
        if ($articleLimit <= 0) {
            $request->withErrorMessage('Invalid request.')->back();
        }

        $queryParams = [
            'categoryId' => $categoryId,
            'sort' => $requestSort,
            'order' => $requestOrder,
            'year' => $requestYearFilter,
            'page' => $page,
            'limit' => $articleLimit,
            'tags' => $requestTags,
            'search' => $search,
        ];

        return $this->_dataProviderFactory->create($queryParams);
    }
}
