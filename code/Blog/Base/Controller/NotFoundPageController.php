<?php

namespace Blog\Base\Controller;

use App\Controller\Controller;
use App\Request\RequestFactory;
use App\DataProvider\DataProvider;
use Blog\Base\View\Page\NotFoundPageViewFactory;

class NotFoundPageController extends Controller
{
    protected NotFoundPageViewFactory $_viewFactory;

    function __construct(
        RequestFactory $requestFactory,
        NotFoundPageViewFactory $viewFactory,
    ) {
        parent::__construct($requestFactory);
        $this->_viewFactory = $viewFactory;
    }

    public function index(): void
    {
        $inputData = new DataProvider();
        $notFoundPageView = $this->_viewFactory->create($inputData);
        $notFoundPageView->render();
    }
}
