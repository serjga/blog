{if is_array($options) && count($options) gt 0}
    <!-- Sort Widget -->
    <div id="blog-articles-sort" class="widget filter">
        <h4 class="widget-header">Sort</h4>
        <select name="select">
            {html_options options=$options}
        </select>
    </div>

    <script type="text/javascript">
        {literal}
            (function(elementId) {
                let state = { active: false };

                function init() {
                    state.active = true;
                    const url = new URL(window.location.href);
                    const sort = url.searchParams.has('sort') ? String(url.searchParams.get('sort')) : '';
                    const order = url.searchParams.has('order') ? String(url.searchParams.get('order')) : '';
                    const selectedOption = sort + '_' + order;

                    if (!sort || !order) {
                        $('#' + elementId + ' select').val('date_desc').niceSelect('update');
                    } else {
                        $('#' + elementId + ' select').val(selectedOption).niceSelect('update');
                    }

                    $('#' + elementId).find('ul .option').each(function(index, element) {
                        $(element).on('click', function() {
                                const value = String(element.dataset.value).trim();
                                if (value !== '') {
                                    const valArr = value.split('_');
                                    const sort = valArr[0] ?? null;
                                    const order = valArr[1] ?? null;
                                    handlerSort(sort, order);
                                }
                            }
                        );
                    });
                }

                function handlerSort(sort, order) {
                    const url = new URL(window.location.href);
                    if (sort === 'date' && order === 'desc') {
                        url.searchParams.delete('sort');
                        url.searchParams.delete('order');
                    } else {
                        url.searchParams.set('sort', sort);
                        url.searchParams.set('order', order);
                    }
                    let cleanSearch = url.search.replace(/%2C/g, ',');
                    window.location.href = url.origin + url.pathname + cleanSearch;
                }

                window.articleListingSortComponentModule = {
                    init: init,
                    getState: function() { return state; }
                };
            })('blog-articles-sort');

        {/literal}
    </script>
{/if}
