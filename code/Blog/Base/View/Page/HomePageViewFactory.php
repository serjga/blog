<?php
namespace Blog\Base\View\Page;

use App\View\BlockViewFactory;

class HomePageViewFactory extends BlockViewFactory
{
    public function create(
        \App\DataProvider\DataProviderInterface $inputDataProvider,
        ?\App\View\ViewInterface $context = null
    ): HomePageView {
        return new HomePageView($inputDataProvider, $context);
    }
}
