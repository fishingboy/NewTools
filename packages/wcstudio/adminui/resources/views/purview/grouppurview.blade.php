@extends('dashboard::layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center full-card">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h1>
                            @lang($langfile . '.group_purview_title')
                        </h1>
                    </div>
                    <div class="card-body min-card">
                        <form>
                            @csrf
                            <div class="select-group">
                                <button type="button" class="btn btn-secondary" id="back">@lang($langfile . '.back')<!--回上一頁--></button>
                            </div>
                        </form>
                        <div class="componentDiv">
                            @component(
                                'adminui::purview.purview_edit',
                                ['menuList' => $menulist, 'menuData' => $menuData, 'groupPurview' => $groupPurview, 'pagename' => $pagename, 'langfile' => $langfile, 'group' => $group])
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            $('#back').click(function(){
                location.href = '{{ route('dashboard.group.management.index') }}';
            });
        });
    </script>
@endsection



