<?php

namespace Blog\Article\Controller;

use App\Request\Url;
use App\Logger\Logger;
use App\Request\RequestFactory;
use App\View\Cache\CacheCleaner;
use App\Response\ResponseFactory;
use Blog\Base\Resource\ClientResource;
use Blog\Article\Resource\ArticleResource;
use Blog\Base\Resource\ClientResourceFactory;
use Blog\Article\Resource\ArticleResourceFactory;
use Blog\Article\View\Page\ArticlePageViewFactory;
use Blog\Article\DataProvider\ArticleDataProviderFactory;

class ArticleController extends \App\Controller\Controller
{
    use Logger;

    protected ArticleResourceFactory $_articleResourceFactory;
    protected ArticleDataProviderFactory $_articleDataProviderFactory;
    protected ResponseFactory $_responseFactory;
    protected ClientResourceFactory $_clientResourceFactory;
    protected ArticlePageViewFactory $_articlePageViewFactory;
    protected Url $_url;

    function __construct(
        RequestFactory $requestFactory,
        ResponseFactory $responseFactory,
        ArticleResourceFactory $articleResourceFactory,
        ArticleDataProviderFactory $articleDataProviderFactory,
        ClientResourceFactory $clientResourceFactory,
        ArticlePageViewFactory $articlePageViewFactory,
        Url $url
    ) {
        parent::__construct($requestFactory);

        $this->_url = $url;
        $this->_articleResourceFactory = $articleResourceFactory;
        $this->_articleDataProviderFactory = $articleDataProviderFactory;
        $this->_responseFactory = $responseFactory;
        $this->_clientResourceFactory = $clientResourceFactory;
        $this->_articlePageViewFactory = $articlePageViewFactory;
    }

    public function article(int $id): void
    {
        try {
            $request = $this->getRequest();

            if ($id < 1) {
                $request->withErrorMessage('Invalid request.')->back();
            }

            $articleDataProvider = $this->_articleDataProviderFactory->create();
            $articleDataProvider->setData('article_id', $id);
            $articlePageView = $this->_articlePageViewFactory->create($articleDataProvider);
            $articlePageView->render();
        } catch (\Throwable $e) {
            $this->log($e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $request->withErrorMessage('Something went wrong. Try again later.')->back();
        }
    }

    public function updateViews(): void
    {
        $response = $this->_responseFactory->create();
        $request = $this->getRequest();
        $queryParams = $request->post();

        $clientHash = $queryParams['hash'] ?? null;
        $articleId = $queryParams['id'] ?? null;

        if (!$clientHash && !$articleId || (int) $articleId < 1) {
            $response->error('Invalid request.');
        } else {
            /** @var ClientResource $clientResource */
            $clientResource = $this->_clientResourceFactory->create();
            if (!$clientResource->hasHash($clientHash)) {
                $clientResource->createHash($clientHash);
            }

            /** @var ArticleResource $articleResource */
            $articleResource = $this->_articleResourceFactory->create();
            $articleResource->updateArticleViews($articleId);
            (new CacheCleaner())->clean([\Blog\Article\DataProvider\ArticleDataProvider::CACHE_ID . '_' . $articleId]);
            $response->success();
        }
    }
}
