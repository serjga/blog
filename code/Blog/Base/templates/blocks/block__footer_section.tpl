<footer class="footer section section-sm">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-9 offset-md-1 offset-lg-0">
                <!-- About -->
                <div class="block about">
                    <!-- footer logo -->
                    <img src="assets/images/logo-footer.png" alt="">
                    <!-- description -->
                    <p class="alt-color">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                </div>
            </div>
            <!-- Link list -->
            <div class="col-lg-4 col-md-3">
                {if is_array($category_list)}
                    <div class="block">
                        <h4>Categories</h4>
                        <ul>
                            {foreach $category_list as $category}
                                <li><a href="{url->getUrl path="/category" id="{$category.id}"}">{$category.name}</a></li>
                            {/foreach}
                        </ul>
                    </div>
                {/if}
            </div>
        </div>
    </div>
</footer>
