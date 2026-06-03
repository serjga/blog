{if !empty($search) }
    <div class="row">
        <div class="col-md-12">
            <div class="search-result">
                {nocache}
                    <h2>Results For "{$search}"</h2>
                {/nocache}
                {if !empty($search_result.total_records)}
                    <p>{$search_result.count} Of {$search_result.total_records} Results</p>
                {/if}
            </div>
        </div>
    </div>
{/if}

{if is_array($articles) && count($articles) gt 0}
    <!-- Category Listing -->
    {foreach $articles as $article}
        <!-- Article -->
        {if is_array($article)}
            <article>
                <!-- Article Image -->
                {if $article.image}
                    <div class="image">
                        <img src="{$article.image}" alt="{$article.title}">
                    </div>
                {/if}

                <!-- Article Title -->
                <h3>{$article.title}</h3>

                <!-- Article Details -->
                <ul class="list-inline">
                    <li class="list-inline-item">
                        <i class="fa fa-calendar"></i>
                        {$article.createdAt|date_format}
                    </li>
                    <li class="list-inline-item">
                        <i class="fa fa-eye"></i>
                        {$article.views}
                    </li>
                </ul>

                <!-- Article Categories -->
                <ul class="list-inline">
                    <i class="fa fa-folder-open-o"></i>
                    {if is_array($article)}
                        {foreach $article.categories as $categoryId => $categoryName}
                            <li class="list-inline-item">
                                <a href="{url->getUrl path="/category" id="{$categoryId}"}">
                                    {$categoryName}
                                </a>
                                {if !$categoryName@last}, {/if}
                            </li>
                        {/foreach}
                    {/if}
                </ul>

                <!-- Article Description -->
                <p class="content">{$article.description}</p>

                <!-- Read More Button -->
                <a href="{url->getUrl path="/article" id="{$article.id}"}" class="read-more-btn">
                    Read More
                </a>
            </article>
        {/if}
    {/foreach}
{else}
    <div class="category-search-filter">
        <div class="row">
            <div class="col">
                <strong>No records found.</strong>
            </div>
        </div>
    </div>
{/if}

{$block__pagination}
