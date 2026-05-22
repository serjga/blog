{if is_array($options) && count($options) gt 0}
    <!-- Sort Widget -->
    <div id="blog-articles-sort" class="widget filter">
        <h4 class="widget-header">Sort</h4>
        <select>
            {html_options options=$options selected=$selected}
        </select>
    </div>

    <script type="text/javascript">
        {literal}
            (function(elementId) {
                let state = { active: false };

                function init() {
                    let el = document.getElementById(elementId);
                    let selectEl = el.querySelector('select');

                    if (selectEl) {
                        selectEl.addEventListener('change', (event) => {
                            handlerSort(event.target.value);
                        });
                    }
                    state.active = true;
                }

                function handlerSort(sortVal) {
                    const url = new URL(window.location.href);
                    if (sortVal === '-1') {
                        url.searchParams.delete('sort');
                    } else {
                        url.searchParams.set('sort', sortVal);
                    }
                    window.location.href = url.toString();
                }

                window.sortComponentModule = {
                    init: init,
                    getState: function() { return state; }
                };
            })('blog-articles-sort');

            window.sortComponentModule.init();
        {/literal}
    </script>
{/if}
