{if is_array($articles) && count($articles) gt 0}
    <!-- Related Articles Widget -->
    <div class="widget related-article">
        <h5 class="widget-header">Related Articles</h5>
        {if is_array($articles)}
            {foreach $articles as $article}
                <!-- Article card -->
                <div class="card-item bg-light">
                    <div class="card">
                        <div class="thumb-content">
                            {if !empty($article.image)}
                                <a href="{url->getUrl path="/article" id="{$article.id}"}">
                                    <img class="card-img-top img-fluid" src="{$article.image}" alt="Article image">
                                </a>
                            {/if}
                        </div>
                        <div class="card-body">
                            <h4 class="card-title" style="font-size: 16px;">
                                <a href="{url->getUrl path="/article" id="{$article.id}"}">{$article.title}</a>
                            </h4>
                        </div>
                    </div>
                </div>
            {/foreach}
        {/if}
    </div>
{/if}
