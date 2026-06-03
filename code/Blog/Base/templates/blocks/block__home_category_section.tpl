{if is_array($category_article_cards) && count($category_article_cards) gt 0}
    <div class="article-block">
        <div class="header">
            {if !empty($icon)}
                <div class="category-icon">
                    <i class="fa {$icon}" style="background: {$main_color}; box-shadow: 0 0 0 4px {$secondary_color};"></i>
                </div>
            {/if}
            <h4>{$name}</h4>
            <div class="visit-category">
                <a href="{$category_url}" class="btn-sm btn-visit-category">
                    Visit
                    <span aria-hidden="true"><i class="fa fa-angle-right"></i></span>
                </a>
            </div>
        </div>

        <div class="article-block-row">
            {foreach $category_article_cards as $article_card}
                {$article_card}
            {/foreach}
        </div>
    </div>
{/if}
