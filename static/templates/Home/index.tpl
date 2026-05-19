{extends file="../layout.tpl"}
{block name=body}

<!--===============================
=            Hero Area            =
================================-->

<section class="hero-area bg-1 text-center overly">
    <!-- Container Start -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Header Contetnt -->
                <div class="content-block">
                    <h1>News & Trend Analysis</h1>
                    <p>Artificial intelligence and quantum computing are no longer just science fiction—they are shaping the reality of tomorrow.</p>

                    {if is_array($popularCategories) && !empty($popularCategories)}
                        <div class="short-popular-category-list text-center">
                            <h2>Popular Category</h2>
                            <ul class="list-inline">
                                {foreach $popularCategories as $category}
                                    <li class="list-inline-item m-2">
                                        <a href="{url->getUrl path="/categories" category="{$category.id}"}"><i class="fa fa-bed"></i> {$category.name}</a>
                                    </li>
                                {/foreach}
                            </ul>
                        </div>
                    {/if}
                </div>
                <!-- Advance Search -->
                <div class="advance-search">
                    <form action="#">
                        <div class="row">
                            <!-- Search -->
                            <div class="col-12">
                                <div class="block d-flex">
                                    <input type="text" onchange="handleSearch(this.value)" class="form-control mb-2 mr-sm-2 mb-sm-0" id="search" placeholder="Search for ...">
                                    <!-- Search Button -->
                                    <button class="btn btn-main">SEARCH</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
    <!-- Container End -->
</section>

<!--===================================
=            Client Slider            =
====================================-->


<!--===========================================
=            Popular deals section            =
============================================-->

<section class="popular-deals section bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                    <h2>Popular Articles</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas, magnam.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- popular articles -->
            {include file='../article_cards.tpl' articles=$popularArticles}
        </div>
    </div>
</section>



<!--==========================================
=            All Category Section            =
===========================================-->

<section class=" section">
    <!-- Container Start -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Section title -->
                <div class="section-title">
                    <h2>All Categories</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perferendis, provident!</p>
                </div>
                <div class="row">
                    <!-- Category list -->
                    {if is_array($categories)}
                        {foreach $categories as $category}
                            <div class="col-12">



                                <div class="category-block">

                                    <div class="header">

                                        <i class="fa fa-laptop icon-bg-1"></i>
                                        <h4>{$category.name}</h4>
                                        <div class="row">
                                            <div class="col-12">
                                            <a href="{url->getUrl path="/categories" category="{$category.id}"}" class="btn btn-main-sm float-right mr-2" style="margin-bottom: -10px;">
                                                More information
                                            </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        {include file='../article_cards.tpl' articles=$category.articles}
                                    </div>
                                </div>
                            </div>
                        {/foreach}
                    {/if}
                    <!-- /Category List -->

                </div>
            </div>
        </div>
    </div>
    <!-- Container End -->
</section>

<script type="text/javascript">
{literal}
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

{/block}
