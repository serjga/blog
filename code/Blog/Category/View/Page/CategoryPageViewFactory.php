<?php
namespace Blog\Category\View\Page;

use App\View\BlockViewFactory;

class CategoryPageViewFactory extends BlockViewFactory
{
    public function create(
        \App\DataProvider\DataProviderInterface $inputDataProvider,
        ?\App\View\ViewInterface $context = null
    ): CategoryPageView {
        return new CategoryPageView($inputDataProvider, $context);
    }
}
