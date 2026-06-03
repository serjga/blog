<!-- Search Widget -->
<div id="blog-articles-search" class="widget search p-0">
    <div class="input-group">
        <input type="text" value="{$search}" class="form-control" placeholder="Search...">
        <span class="input-group-addon"><i class="fa fa-search"></i></span>
    </div>
</div>

<script type="text/javascript">
    {literal}
        (function(elementId) {
            let state = { active: false };

            function init() {
                let el = document.getElementById(elementId);
                let selectEl = el.querySelector('input');

                if (selectEl) {
                    selectEl.addEventListener('change', (event) => {
                        handleSearch(event.target.value);
                    });
                }
                state.active = true;
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
                url.searchParams.delete('id');
                window.location.href = url.toString();
            }

            window.articlesSearchComponentModule = {
                init: init,
                getState: function() { return state; }
            };
        })('blog-articles-search');

        window.articlesSearchComponentModule.init();
    {/literal}
</script>
