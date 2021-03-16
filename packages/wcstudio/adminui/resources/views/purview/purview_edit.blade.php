<form id="purview_form">
    @csrf
    <div class="container group-info mt-3 mb-4" data-purview="{{ json_encode($groupPurview) }}">
        @if(!empty($group))
            <input type="hidden" name="group_id" value="{{ $group->group_id }}"/>
            <div class="row mt-2">
                <div class="col-2">
                    @lang($langfile . '.group_id')
                </div>
                <div class="col group-id">
                    {{ $group->group_id }}
                </div>
            </div>
        @endif
        <div class="row mt-2">
            <div class="col-2">
                @lang($langfile . '.group_name')
            </div>
            <div class="col group-name input-group input-group-sm">
                <input type="text" class="form-control" name="group_name" @if(!empty($group)) value="{{ $group->group_name }}" @endif/>
            </div>
        </div>
        @if(!empty($group))
            <div class="row mt-2">
                <div class="col-2">
                    @lang($langfile . '.created_at')
                </div>
                <div class="col">
                    {{ $group->created_at }}
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-2">
                    @lang($langfile . '.created_user')
                </div>
                <div class="col created-user">
                    {{ $group->created_user }}
                </div>
            </div>
        @endif
        <div class="row mt-2">
            <div class="col-2">
                @lang($langfile . '.status')
            </div>
            <fieldset class="form-group pl-3">
                <div class="status form-check-inline">
                    <input type="radio" class="form-check-input" id="statusY" name="status" value="Y" checked/><label class="form-check-label" for="statusY">@lang($langfile . '.group_status_Y')</label>
                </div>
                <div class="status form-check-inline">
                    <input type="radio" class="form-check-input" id="statusN" name="status" value="N" @if(!empty($group) && $group->status == 'N') checked @endif/><label class="form-check-label" for="statusN">@lang($langfile . '.group_status_N')</label>
                </div>
            </fieldset>
        </div>
    </div>

    <table class="purview-table table">
        <caption style="caption-side: top">@lang($langfile . '.choose_menu_function')</caption>
        @foreach($menuData as $menuLayer1)
            <tr class="menu-row">
                <td class="layer1"><div class="checkbox pl-3"><label class="checkbox-inline"><input type="checkbox" class="form-check-input check-layer-1 " name="{{ $menuLayer1['menu_id'] }}" id="menu{{ $menuLayer1['menu_id'] }}">{{ $menuLayer1['menu_name'] }}</label></div></td>
                <td>
                    <table class="inner-purview-table">
                        @foreach($menuLayer1['sub'] as $menuLayer2)
                            <tr>
                                <td class="layer2"><div class="checkbox pl-3"><label class="checkbox-inline"><input type="checkbox" class="form-check-input check-layer-2" name="{{ $menuLayer2['menu_id'] }}" id="menu{{ $menuLayer2['menu_id'] }}"/>{{ $menuLayer2['menu_name'] }}</label></div></td>
                                <td class="layer3">
                                    @foreach($menuLayer2['sub'] as $menuLayer3)
                                        <div class="checkbox pl-3"><label class="checkbox-inline"><input type="checkbox" class="form-check-input mr-2" name="{{ $menuLayer3['menu_id'] }}" id="menu{{ $menuLayer3['menu_id'] }}"/>{{$menuLayer3['menu_name']}}</label></div>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        @endforeach
    </table>
    <button type="button" class="btn btn-info save-group-purview">@lang($langfile . '.save')</button>
</form>

<script>
    $(function() {
        var purviewData = $('.group-info').data('purview');
        $('input[type=checkbox]').prop('checked', false);
        $.each(purviewData, function (menu, menuData) {
            if (menuData.status == 'Y')
            {
                $('#menu'+ menuData.menu_id).prop('checked', true);
            }
        });

        $('.check-layer-1, .check-layer-2').click(function(){
            var status = $(this).prop('checked');
            $(this).parent('label').parent('div').parent('td').parent('tr').find('input').prop('checked', status);
        });

        $('.save-group-purview').click(function(){

            if($("input[name='group_name']").val() == ''){
                alert("@lang($langfile . '.please_enter_group_name')");
                return;
            }

            if($("input[name='status']").val() == ''){
                alert("@lang($langfile . '.please_choose_group_status')");
                return;
            }

            $.ajax({
                url: "{{ route('admin.group.management.purview.edit') }}",
                type: "POST",
                data: $('#purview_form').serialize()
                ,
                success: function (response) {
                    alert("@lang($langfile . '.edit_purview_success')");
                    location.href = "{{ route('admin.group.management.purview.index') }}?group_id=" + response;
                },
                error: function() {
                    alert("@lang($langfile . '.edit_purview_fail')");
                }
            });
        });
    });
</script>
