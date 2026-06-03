{if is_array($related_article_cards) && count($related_article_cards) gt 0}
    <div class="widget related-article">
        <h5 class="widget-header">Related Articles</h5>
        {foreach $related_article_cards as $card}
            {$card}
        {/foreach}
    </div>
{/if}
