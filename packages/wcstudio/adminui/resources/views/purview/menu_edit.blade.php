<div id="nestedSortable" class="accordion">
    @foreach ($allmenu as $menuLayer1)
        @if(count($menuLayer1['sub']) > 0)
        <div class="card mb-0">

                <i class="fas fa-chevron-right"></i>
                <p class="display-c">
                    @lang($menuLayer1['menu_name'])
                    @if($menuLayer1['menu_visible'] === "Y")
                        <i class="fas fa-eye ml-2"></i>
                    @elseif($menuLayer1['menu_visible'] === "N")
                        <i class="fas fa-eye-slash ml-2"></i>
                    @endif
                    @if($menuLayer1['status'] === "Y")
                        <span class="badge bg-cus-green text-white ml-2">@lang($langfile . '.menu_status_Y')</span>
                    @elseif($menuLayer1['status'] === "N")
                        <span class="badge bg-secondary ml-2">@lang($langfile . '.menu_status_N')</span>
                    @endif
                </p>
                <i class="cil-pen"></i>
            </div>
        @else
        <div class="list-group-item nested-item first-item" data-id="{{$menuLayer1['menu_id']}}">
            <div class="nested-item-left-space"></div>
            <p class="display-c">
                @lang($menuLayer1['menu_name'])
                @if($menuLayer1['menu_visible'] === "Y")
                    <i class="fas fa-eye ml-2"></i>
                @elseif($menuLayer1['menu_visible'] === "N")
                    <i class="fas fa-eye-slash ml-2"></i>
                @endif
                @if($menuLayer1['status'] === "Y")
                    <span class="badge bg-cus-green text-white ml-2">@lang($langfile . '.menu_status_Y')</span>
                @elseif($menuLayer1['status'] === "N")
                    <span class="badge bg-secondary ml-2">@lang($langfile . '.menu_status_N')</span>
                @endif
            </p>
            <button type="button" class="btn btn-sm btn-pill btn-ghost-dark open-modal ml-1" value="{{$menuLayer1['menu_id']}}">
                <i class="fas fa-pen"></i>
            </button>
        @endif
            <div class="list-group nested-sortable panel">
                @foreach ($menuLayer1['sub'] as $menuLayer2)
                    @if(count($menuLayer2['sub']) > 0)
                    <div class="nested-item second-item p-0" data-id="{{$menuLayer2['menu_id']}}">
                        <div class="accordion">
                            <i class="fas fa-chevron-right"></i>
                            <p class="display-c">
                                @lang($menuLayer2['menu_name'])
                                @if($menuLayer2['menu_visible'] === "Y")
                                    <i class="fas fa-eye ml-2"></i>
                                @elseif($menuLayer2['menu_visible'] === "N")
                                    <i class="fas fa-eye-slash ml-2"></i>
                                @endif
                                @if($menuLayer2['status'] === "Y")
                                    <span class="badge bg-cus-green text-white ml-2">@lang($langfile . '.menu_status_Y')</span>
                                @elseif($menuLayer2['status'] === "N")
                                    <span class="badge bg-secondary ml-2">@lang($langfile . '.menu_status_N')</span>
                                @endif
                            </p>
                            <button type="button" class="btn btn-sm btn-pill btn-ghost-dark open-modal ml-1" value="{{$menuLayer2['menu_id']}}">
                                <i class="fas fa-pen"></i>
                            </button>
                        </div>
                    @else
                    <div class="nested-item second-item" data-id="{{$menuLayer2['menu_id']}}">
                        <div class="nested-item-left-space"></div>
                        <p class="display-c">
                            @lang($menuLayer2['menu_name'])
                            @if($menuLayer2['menu_visible'] === "Y")
                                <i class="fas fa-eye ml-2"></i>
                            @elseif($menuLayer2['menu_visible'] === "N")
                                <i class="fas fa-eye-slash ml-2"></i>
                            @endif
                            @if($menuLayer2['status'] === "Y")
                                <span class="badge bg-cus-green text-white ml-2">@lang($langfile . '.menu_status_Y')</span>
                            @elseif($menuLayer2['status'] === "N")
                                <span class="badge bg-secondary ml-2">@lang($langfile . '.menu_status_N')</span>
                            @endif
                        </p>
                        <button type="button" class="btn btn-sm btn-pill btn-ghost-dark open-modal ml-1" value="{{$menuLayer2['menu_id']}}">
                            <i class="fas fa-pen"></i>
                        </button>
                    @endif
                        <div class="list-group nested-sortable panel">
                            @foreach ($menuLayer2['sub'] as $menuLayer3)
                                <div class="nested-item third-item" data-id="{{$menuLayer3['menu_id']}}">
                                    <div class="nested-item-left-space"></div>
                                    <p class="display-c">
                                        @lang($menuLayer3['menu_name'])
                                        @if($menuLayer3['menu_visible'] === "Y")
                                            <i class="fas fa-eye ml-2"></i>
                                        @elseif($menuLayer3['menu_visible'] === "N")
                                            <i class="fas fa-eye-slash ml-2"></i>
                                        @endif
                                        @if($menuLayer3['status'] === "Y")
                                            <span class="badge bg-cus-green text-white ml-2">@lang($langfile . '.menu_status_Y')</span>
                                        @elseif($menuLayer3['status'] === "N")
                                            <span class="badge bg-secondary ml-2">@lang($langfile . '.menu_status_N')</span>
                                        @endif
                                    </p>
                                    <button type="button" class="btn btn-sm btn-pill btn-ghost-dark open-modal ml-1" value="{{$menuLayer3['menu_id']}}">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

<script>

    var nestedSortables = [];
    var sortables = [];
    var nestedSortableCache;

    const nestedQuery = '.nested-sortable';
    const root = document.getElementById('nestedSortable');
    //
    // $(document).ready(function() {
    //     init();
    // });

    function serialize(sortable) {
        var serialized = [];
        var children = [].slice.call(sortable.children);
        for (var i in children) {
            var nested = children[i].querySelector(nestedQuery);
            serialized.push({
                id: children[i].dataset['id'],
                children: nested ? serialize(nested) : []
            });
        }
        return serialized;
    }

    function resetNestedSortables() {
        $("#nestedSortable").html(nestedSortableCache);
        init();
    }

    // ???menu list?????????
    function depth(obj) {
        var maxdepth = 0;
        if (typeof obj == 'object') {
            for (var key in obj) {
                var dpth = depth(obj[key]);
                if ( dpth > maxdepth ){
                    maxdepth = dpth;
                }
            }
        }
        if (Object.keys(obj).includes('children')) {
            return maxdepth + 1;
        }
        return maxdepth;
    }

    function init() {
        sortables = [];
        nestedSortables = [].slice.call(document.querySelectorAll('.nested-sortable'));

        // Loop through each nested sortable element
        for (let i = 0; i < nestedSortables.length; i++) {
            sortables[i] = new Sortable(nestedSortables[i], {

                group: 'nested',
                animation: 150,
                ghostClass: 'sortable-ghost',
                sort: true,
                srcoll: true,
                swapThreshold: 0.2,

                // true?????????????????????????????????(cursor: grabbing), ?????????????????????onclick
                // forceFallback: true, // ignore the HTML5 DnD behaviour and force the fallback to kick in

                // Element is chosen
                onChoose: function () {
                    $('html').addClass('grabbing');// Dragging started
                    $("#nestedSortable div").removeClass('sortable-ghost');
                    nestedSortableCache = $("#nestedSortable").removeClass('sortable-ghost').html();
                },

                // Element dragging started
                onStart: function () {
                    $('html').addClass('grabbing'); // Dragging started
                },

                // Element is unchosen
                onUnchoose: function () {
                    $('html').removeClass('grabbing');
                },

                // Element dragging ended
                onEnd: function (/**Event*/evt) {
                    $('html').removeClass('grabbing');

                    if (depth(serialize(root)) > 3) {
                        alert("@lang($langfile . '.no_more_than_3_layers')");
                        resetNestedSortables();
                        return false;
                    }

                    var itemEl = evt.item;  // dragged HTMLElement
                    var toEl = evt.to;
                    var formEl = evt.from;

                    if (itemEl.classList.contains("first-item") && toEl.parentNode.classList.contains("first-item")) {
                        // ????????? ?????? ????????? ????????? ?????? ?????????

                        if (!toEl.parentNode.classList.contains("p-0")) {
                            // ????????? ?????? ???????????????[???]

                            toEl.parentNode.classList.add("p-0");
                            let htmlCache = toEl.parentNode.innerHTML;
                            htmlCache = htmlCache.replace('<div class="nested-item-left-space"></div>', '<div class="accordion"><i class="fas fa-chevron-right"></i>');
                            htmlCache = htmlCache.replace('</button>', '</button></div>');

                            if (itemEl.classList.contains("p-0")) {
                                // ?????????[?????????] ?????? ???????????????[???]

                                htmlCache = htmlCache.replaceAll('second-item', 'third-item');
                                htmlCache = htmlCache.replace('list-group-item', '');
                                htmlCache = htmlCache.replace('first-item', 'second-item');

                            } else if (!itemEl.classList.contains("p-0")) {
                                // ?????????[???] ?????? ?????????[???]

                                htmlCache = htmlCache.replace('list-group-item', '');
                                htmlCache = htmlCache.replace('first-item', 'second-item');
                            }

                            toEl.parentNode.innerHTML = htmlCache;
                        } else if (toEl.parentNode.classList.contains("p-0")) {
                            // ?????????[???] ?????? ?????????[?????????]

                            if (!itemEl.classList.contains("p-0")) {
                                // ?????????[???] ?????? ?????????[?????????] ?????? ???2???
                                itemEl.classList.remove("list-group-item", "first-item");
                                itemEl.classList.add("second-item");
                            } else if (itemEl.classList.contains("p-0")) {
                                // ?????????[?????????] ?????? ?????????[?????????]
                                itemEl.classList.remove("first-item", "list-group-item");
                                itemEl.classList.add("second-item");

                                let htmlCache = itemEl.innerHTML;
                                htmlCache = htmlCache.replaceAll('second-item', 'third-item');
                                itemEl.innerHTML = htmlCache;
                            }
                        }
                    } else if (itemEl.classList.contains("first-item") && toEl.parentNode.classList.contains("second-item")) {
                        // ????????? ?????? ???2??? ???????????????
                        if (!toEl.parentNode.classList.contains("p-0")) {
                            // ????????? ?????? ???2???[???] ???????????????
                            toEl.parentNode.classList.add("p-0");

                            let htmlCache = toEl.parentNode.innerHTML;
                            htmlCache = htmlCache.replace('<div class="nested-item-left-space"></div>', '<div class="accordion"><i class="fas fa-chevron-right"></i>');
                            htmlCache = htmlCache.replace('</button>', '</button></div>');
                            htmlCache = htmlCache.replace('first-item', 'third-item');
                            htmlCache = htmlCache.replace('list-group-item', '');
                            htmlCache = htmlCache.replace('draggable="false"', '');
                            toEl.parentNode.innerHTML = htmlCache;
                        } else if (toEl.parentNode.classList.contains("p-0")) {
                            // ????????? ?????? ???2???[?????????] ???????????????
                            itemEl.classList.remove("list-group-item", "first-item");
                            itemEl.classList.add("third-item");
                        }

                    } else if (itemEl.classList.contains("second-item") && toEl.parentNode.classList.contains("componentDiv")){
                        // ???????????? ?????? ????????????
                        itemEl.classList.remove("second-item");
                        itemEl.classList.add("list-group-item", "first-item");

                        // if ??????????????????/ ????????????????????? ??????????????????????????????
                        if (itemEl.innerHTML.search("third-item") > 1) {
                            let htmlCache = itemEl.innerHTML;
                            htmlCache = htmlCache.replace(/third-item/g, 'second-item');

                            let i = 0;
                            htmlCache = htmlCache.replace(/<\/button>/g, function() {
                                return i++ === 0 ? '</button>' : '</button><div class="list-group nested-sortable panel"></div>';
                            });
                            itemEl.innerHTML = htmlCache;

                        }
                    } else if (itemEl.classList.contains("second-item") && toEl.parentNode.classList.contains("first-item")) {
                        // ???????????? ?????? ???????????????
                        if (!toEl.parentNode.classList.contains("p-0")) {
                            // ?????????[???] ?????? ???1???[???]
                            toEl.parentNode.classList.add("p-0");

                            let htmlCache = toEl.parentNode.innerHTML;
                            htmlCache = htmlCache.replace('<div class="nested-item-left-space"></div>', '<div class="accordion"><i class="fas fa-chevron-right"></i>');
                            htmlCache = htmlCache.replace('</button>', '</button></div>');
                            htmlCache = htmlCache.replace('draggable="false"', '');

                            toEl.parentNode.innerHTML = htmlCache;
                        }
                    } else if (itemEl.classList.contains("second-item") && toEl.parentNode.classList.contains("second-item")){
                        // ???????????? ?????? ????????? ?????????
                        if (!toEl.parentNode.classList.contains("p-0")) {
                            // ?????????[???] ?????? ?????????[???]
                            toEl.parentNode.classList.add("p-0");
                            let htmlCache = toEl.parentNode.innerHTML;
                            htmlCache = htmlCache.replace('<div class="nested-item-left-space"></div>', '<div class="accordion"><i class="fas fa-chevron-right"></i>');
                            htmlCache = htmlCache.replace('</button>', '</button></div>');
                            htmlCache = htmlCache.replace('second-item', 'third-item');
                            htmlCache = htmlCache.replace('draggable="false"', '');
                            toEl.parentNode.innerHTML = htmlCache;

                        } else if (toEl.parentNode.classList.contains("p-0")) {
                            // ????????? ?????? ?????????[?????????]
                            itemEl.classList.remove("second-item");
                            itemEl.classList.add("third-item");
                        }
                    } else if (itemEl.classList.contains("third-item") && toEl.parentNode.classList.contains("componentDiv")){
                        // ??????3??? ??? ???1??????
                        itemEl.classList.remove("third-item");
                        itemEl.classList.add("list-group-item", "first-item");

                        let htmlCache = itemEl.innerHTML;
                        itemEl.innerHTML = htmlCache + '<div class="list-group nested-sortable panel">';

                    } else if (itemEl.classList.contains("third-item") && toEl.parentNode.classList.contains("first-item")){
                        // ???3??? ?????? ???1???
                        if (!toEl.parentNode.classList.contains("p-0")) {
                            // ???3???[???] ?????? ???1???[???]
                            toEl.parentNode.classList.add("p-0");

                            let htmlCache = toEl.parentNode.innerHTML;
                            htmlCache = htmlCache.replace('<div class="nested-item-left-space"></div>', '<div class="accordion"><i class="fas fa-chevron-right"></i>');
                            htmlCache = htmlCache.replace('</button>', '</button></div>');
                            htmlCache = htmlCache.replace('third-item', 'second-item');
                            htmlCache = htmlCache.replace('</div></div>', '<div class="list-group nested-sortable panel"></div></div></div>');
                            htmlCache = htmlCache.replace('draggable="false"', '');
                            toEl.parentNode.innerHTML = htmlCache;
                        } else if (toEl.parentNode.classList.contains("p-0")) {
                            // ???3???[???] ?????? ???1???[?????????]
                            let htmlCache = itemEl.innerHTML;
                            itemEl.classList.remove("third-item");
                            itemEl.classList.add("second-item");
                            itemEl.innerHTML = htmlCache + '<div class="list-group nested-sortable panel"></div>';
                        }
                    } else if (itemEl.classList.contains("third-item") && toEl.parentNode.classList.contains("second-item")){
                        if (!toEl.parentNode.classList.contains("p-0")) {
                            // ???3??? ?????? ???2???[???]
                            toEl.parentNode.classList.add("p-0");
                            let htmlCache = toEl.parentNode.innerHTML;
                            htmlCache = htmlCache.replace('<div class="nested-item-left-space"></div>', '<div class="accordion"><i class="fas fa-chevron-right"></i>');
                            htmlCache = htmlCache.replace('</button>', '</button></div>');
                            htmlCache = htmlCache.replace('draggable="false"', '');
                            toEl.parentNode.innerHTML = htmlCache;
                        }
                    }

                    // ??????????????????????????????, ??????children ??????, ??????????????????????????????(????????????, ??????accordion)
                    if (formEl.children.length === 0) {
                        formEl.parentNode.classList.remove("p-0");

                        let htmlCache = formEl.parentNode.innerHTML;
                        htmlCache = htmlCache.replace('<div class="accordion active">', '<div class="nested-item-left-space"></div>');
                        htmlCache = htmlCache.replace('<i class="fas fa-chevron-down"></i>', '');
                        htmlCache = htmlCache.replace('</button></div>', '</button>');
                        htmlCache = htmlCache.replace('style="display: block;"', '');
                        formEl.parentNode.innerHTML = htmlCache;
                    }

                    init();
                },
            });
        }
    }

</script>
