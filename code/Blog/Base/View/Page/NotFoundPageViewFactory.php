<?php
namespace Blog\Base\View\Page;

use App\View\BlockViewFactory;

class NotFoundPageViewFactory extends BlockViewFactory
{
    public function create(
        \App\DataProvider\DataProviderInterface $inputDataProvider,
        ?\App\View\ViewInterface $context = null
    ): NotFoundPageView {
        return new NotFoundPageView($inputDataProvider, $context);
    }
}
