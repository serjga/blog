{if is_array($articles)}
    {foreach $articles as $article}
        <div class="col p-0">
            <!-- article card -->
            <div class="product-item bg-light">
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
        </div>
    {/foreach}
{/if}
