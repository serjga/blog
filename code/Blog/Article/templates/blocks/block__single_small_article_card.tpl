{if !empty($id)}
    <div class="card-item bg-light">
        <div class="card">
            <div class="thumb-content">
                {if !empty($main_image)}
                    <a href="{$article_url}">
                        <img class="card-img-top img-fluid" src="{$main_image}" alt="Article image">
                    </a>
                {/if}
            </div>
            <div class="card-body">
                <h4 class="card-title" style="font-size: 16px;">
                    <a href="{$article_url}">{$title}</a>
                </h4>
            </div>
        </div>
    </div>
{/if}
