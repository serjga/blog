{if is_array($options) && count($options) gt 0}
    <!-- Archive Filter Widget -->
    <div id="blog-articles-archive-filter" class="widget archive">
        <h5 class="widget-header">Archives</h5>
        <ul class="archive-list">
            {foreach $options as $item}
                <li data-value="{$item}">{$item}</li>
            {/foreach}
        </ul>
    </div>

    <script type="text/javascript">
        {literal}
            (function(elementId) {
                let state = { active: false };

                function init() {
                    state.active = true;
                    $('#' + elementId).find('ul li')
                        .each(function(index, element) {
                            const url = new URL(window.location.href);
                            const year = url.searchParams.has('year') ? String(url.searchParams.get('year')) : '';
                            const elValue = element.dataset.value;
                            if (year === elValue) {
                                element.classList.add('active');
                            }

                            $(element).on('click', function() {
                                    const value = String(element.dataset.value).trim();
                                    if (value !== '') {
                                        handlerFilter(value);
                                    }
                                }
                            );
                        });
                }

                function handlerFilter(value) {
                    const url = new URL(window.location.href);
                    url.searchParams.set('year', value);
                    url.searchParams.delete('page');
                    url.searchParams.delete('search');
                    let cleanSearch = url.search.replace(/%2C/g, ',');
                    window.location.href = url.origin + url.pathname + cleanSearch;
                }

                window.archiveFilterComponentModule = {
                    init: init,
                    getState: function() { return state; }
                };
            })('blog-articles-archive-filter');

        {/literal}
    </script>
{/if}
