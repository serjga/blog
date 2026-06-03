<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h2>All Categories</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Perferendis, provident!</p>
                </div>
                <div class="row">
                    {foreach $categories as $category_section}
                        {$category_section}
                    {/foreach}
                </div>
            </div>
        </div>
    </div>
</section>
