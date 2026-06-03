{if count($recommended_article_cards) gt 0}
    <div class="block dynamic-block">
        <h4>You Might Be Interested</h4>
        <div class="article-block-row">
            {foreach $recommended_article_cards as $card}
                {$card}
            {/foreach}
        </div>
    </div>
{/if}
