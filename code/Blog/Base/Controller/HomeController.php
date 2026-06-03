<?php

namespace Blog\Base\Controller;

use App\Request\Url;
use App\Logger\Logger;
use App\Controller\Controller;
use App\Request\RequestFactory;
use App\DataProvider\DataProvider;
use Blog\Base\View\Page\HomePageViewFactory;
use Blog\Article\Resource\ArticleResourceFactory;
use Blog\Category\Resource\CategoryResourceFactory;

class HomeController extends Controller
{
    use Logger;
    protected CategoryResourceFactory $_categoryResourceFactory;
    protected ArticleResourceFactory $_articleResourceFactory;
    protected HomePageViewFactory $_homePageViewFactory;
    protected Url $_url;

    function __construct(
        RequestFactory $requestFactory,
        ArticleResourceFactory $articleResourceFactory,
        CategoryResourceFactory $categoryResourceFactory,
        HomePageViewFactory $homePageViewFactoryFactory,
        Url $url
    ) {
        parent::__construct($requestFactory);
        $this->_articleResourceFactory = $articleResourceFactory;
        $this->_categoryResourceFactory = $categoryResourceFactory;
        $this->_homePageViewFactory = $homePageViewFactoryFactory;
        $this->_url = $url;
    }

    public function index(): void
    {
        try {
            $inputData = new DataProvider();
            $homePageView = $this->_homePageViewFactory->create($inputData);
            $homePageView->render();
        } catch(\Throwable $e) {
            $this->log($e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $request = $this->getRequest();
            $request->withErrorMessage('Something went wrong. Try again later.')->back();
        }
    }
}
