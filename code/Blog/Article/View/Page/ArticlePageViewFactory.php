<?php
namespace Blog\Article\View\Page;

use App\View\BlockViewFactory;

class ArticlePageViewFactory extends BlockViewFactory
{
    public function create(
        \App\DataProvider\DataProviderInterface $inputDataProvider,
        ?\App\View\ViewInterface $context = null
    ): ArticlePageView {
        return new ArticlePageView($inputDataProvider, $context);
    }
}
