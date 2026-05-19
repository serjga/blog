<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav class="navbar navbar-expand-lg  navigation">
                    <a class="navbar-brand" href="{url->getUrl path="/"}">
                        <img src="{url->getUrl path="/pic/logo.png"}" alt="">
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto main-nav ">
                            <li class="nav-item active">
                                <a class="nav-link" href="{url->getUrl path="/"}">Home</a>
                            </li>
                            <li class="nav-item dropdown dropdown-slide">
                                <a class="nav-link dropdown-toggle" href="{url->getUrl path="/categories"}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Categories <span><i class="fa fa-angle-down"></i></span>
                                </a>
                                <!-- Dropdown list -->
                                <div class="dropdown-menu dropdown-menu-right">
                                    {foreach $categoryList as $category}
                                        <a class="dropdown-item" href="{url->getUrl path="/categories" category="{$category.id}"}">
                                            {$category.name}
                                        </a>
                                    {foreachelse}
                                        No categories found.
                                    {/foreach}
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</section>
