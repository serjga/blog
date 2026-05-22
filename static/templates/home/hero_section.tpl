<!--===============================
=            Hero Area            =
================================-->

<section class="hero-area bg-1 text-center overly">
    <!-- Container Start -->
    <div class="container position-relative">
        <div class="row">
            <div class="col-md-12">
                <!-- Header Contetnt -->
                <div class="content-block">
                    <h1>News & Trend Analysis</h1>
                    <p>Artificial intelligence and quantum computing are no longer just science fiction—they are shaping the reality of tomorrow.</p>

                    {if is_array($categories) && !empty($categories)}
                        <div class="short-popular-category-list text-center">
                            <h2>Popular Category</h2>
                            <ul class="list-inline">
                                {foreach $categories as $category}
                                    <li class="list-inline-item m-2">
                                        <a href="{url->getUrl path="/categories" category="{$category.id}"}">
                                            <i class="fa fa-bed"></i> {$category.name}
                                        </a>
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
                                    <button class="search-btn">SEARCH</button>
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
