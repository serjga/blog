<div class="sidebar">

    <!-- Search Widget -->
    <div class="widget search p-0">
        <div class="input-group">
            <input type="text" value="{$search}" onchange="handleSearch(this.value)" class="form-control" id="expire" placeholder="Search...">
            <span class="input-group-addon"><i class="fa fa-search"></i></span>
        </div>
    </div>

    <!-- Category Widget -->
    {if count($categoryList) gt 0}
        <div class="widget category-list">
            <h4 class="widget-header">
                <a href="{url->getUrl path="/categories"}">
                    All Category
                </a>
            </h4>
            <ul class="category-list">
                {foreach $categoryList as $category}
                    <li><a href="{url->getUrl path="/categories" category="{$category.id}"}">{$category.name} <span>{$category.articleCount}</span></a></li>
                {/foreach}
            </ul>
        </div>
    {/if}

    <!-- Archive Widget -->
    {if count($sortOptions ) gt 0}
        <div class="widget filter">
            <h4 class="widget-header">Sort</h4>
            <select name="year" onchange="handleSort(this.value)">
                {html_options options=$sortOptions selected=$selectedYear}
            </select>
        </div>
    {/if}

    <!-- Related Articles Widget -->
    {if count($relatedArticles) gt 0}
        <div class="widget related-store">
            <!-- Widget Header -->
            <h5 class="widget-header">Related Articles</h5>
            {include file='./sidebar_cards.tpl'  articles=$relatedArticles}
        </div>
    {/if}

    {if count($tagList) gt 0}
        <div class="widget product-shorting">
            <h4 class="widget-header">Tags</h4>

            {foreach $tagList as $tag}
                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" value="{$tag.id}">
                        {$tag.label}
                    </label>
                </div>
            {/foreach}
        </div>
    {/if}

    <!-- Sort Widget -->
    {if count($yearOptions) gt 0}
        <div class="widget archive">
            <!-- Widget Header -->
            <h5 class="widget-header">Archives</h5>
            {foreach $yearOptions as $year}
                <ul class="archive-list">
                    <li><a href="{url->getUrl path="/categories" year="{$year}"}">{$year}</a></li>
                </ul>
            {/foreach}
        </div>
    {/if}

</div>

<script type="text/javascript">
    {literal}
    function handleSort(sortVal) {
        const url = new URL(window.location.href);
        if (sortVal === '-1') {
            url.searchParams.delete('sort');
        } else {
            url.searchParams.set('sort', sortVal);
        }
        window.location.href = url.toString();
    }

    function handleFilterYear(year) {
        const url = new URL(window.location.href);
        url.search = '';
        if (year !== '*') {
            url.searchParams.set('year', year);
        }
        window.location.href = url.toString();
    }

    function handleSearch(search) {
        let value = search.trim();
        const url = new URL(window.location.href);
        if (value === '') {
            url.searchParams.delete('search');
        } else {
            url.searchParams.set('search', search);
        }
        url.pathname = '/categories';
        url.searchParams.delete('page');
        url.searchParams.delete('category');
        url.searchParams.delete('year');
        window.location.href = url.toString();
    }
    {/literal}
</script>
