<?php
namespace Blog\Article\View\Block;

use App\Request\Url;
use App\View\BlockView;
use Blog\Article\Registry;

class SingleArticleSmallCardView extends BlockView
{
    protected string $_template = 'blocks/block__single_small_article_card';

    public function render(bool $return = false): ?string
    {
        $articleId = $this->getData('article_id');

        $this->_template = (new Registry())->getTemplatePath($this->_template);

        if (!$this->hasCache()) {
            $url = new Url();
            $articleMainImage = $url->getImageUrl(['path' => $this->getData('image')]);
            $articleTitle = $this->getData('title');
            $articleLink = $url->getUrl(['path' => '/article', 'id' => $articleId]);

            $this->addTemplateVariable('id', $articleId)
                ->addTemplateVariable('main_image', $articleMainImage)
                ->addTemplateVariable('title', $articleTitle)
                ->addTemplateVariable('article_url', $articleLink);
        }

        return parent::render($return);
    }
}
