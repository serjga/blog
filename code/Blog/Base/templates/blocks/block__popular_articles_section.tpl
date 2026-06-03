{if is_array($top_popular_article_cards) && count($top_popular_article_cards) gt 0}
    <section class="popular-articles-section section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h2>Popular Articles</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas, magnam.</p>
                    </div>
                </div>
            </div>
            <div class="article-block-row">
                {foreach $top_popular_article_cards as $article_card}
                    {$article_card}
                {/foreach}
            </div>
        </div>
    </section>
{/if}
