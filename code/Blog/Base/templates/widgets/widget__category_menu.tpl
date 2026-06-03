{if is_array($sidebar_category_menu_widget) && count($sidebar_category_menu_widget) gt 0}
    <!-- Category List Widget -->
    <div id="category-sidebar-menu-widget" class="widget sidebar-category">
        <h4 class="widget-header">
            <a href="{url->getUrl path="/categories"}">
                All Category
            </a>
        </h4>
        <ul class="category-list">
            {foreach $sidebar_category_menu_widget as $menu_item}
                {$menu_item}
            {/foreach}
        </ul>
    </div>

    <script type="text/javascript">
        {literal}
            (function(elementId) {
                let state = { active: false };

                function init() {
                    state.active = true;
                    const currentPath = window.location.pathname;
                    if (currentPath === '/category') {
                        $('#' + elementId).find('ul li')
                            .each(function(index, element) {
                                const url = new URL(window.location.href);
                                const id = url.searchParams.has('id') ? String(url.searchParams.get('id')) : '';
                                const itemId = element.dataset.id;
                                if (id === itemId) {
                                    element.classList.add('active');
                                }
                            });
                    }
                }

                window.categorySidebarMenuComponentModule = {
                    init: init,
                    getState: function() { return state; }
                };
            })('category-sidebar-menu-widget');

        {/literal}
    </script>
{/if}
