@extends('adminui::layouts.app')

@section('head')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
@endsection

@section('content')
    <div class="container-fluid">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger" role="alert">
                    {{ $error }}
                    <button type="button" class="outline-none close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endforeach
        @endif

        @if(session()->has('message'))
            <div class="alert alert-success" role="alert">
                {{ session()->get('message') }}
                <button type="button" class="outline-none close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row justify-content-center full-card">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h2>
                                    選單管理
                                </h2>

                            </div>
                            <div class="btn-toolbar d-none d-md-block" role="toolbar" aria-label="Toolbar with button groups">
                                <button type="button" class="btn btn-info mb-2" id="addMenuBtn">
                                @lang($langfile . '.add_menu')<!--新增功能列表-->
                                </button>

                                <button type="button" class="btn btn-danger mb-2" id="saveBtn">
                                    @lang($langfile . '.save')
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="componentDiv">
                            @component(
                                'adminui::purview.menu_edit',
                                ['menuList' => $menulist, 'allmenu' => $tablelist, 'pagename' => $pagename, 'langfile' => $langfile])
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="menuModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="menuModalLabel">menu</h5>
                    <button type="button" class="close outline-none" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="menuForm" method="post" action="">
                    <div class="modal-body">
                        @csrf
                        {{-- parent_layer_0 --}}
                        <div class="form-group row parent-menu-0">
                            <label for="addMenuParentLayer0" class="col-sm-3 col-form-label required">@lang($langfile . '.parent_layer_0')</label>
                            <div class="col-sm-9">
                                <select id="addMenuParentLayer0" name="parent_layer_0" class="form-control parent-layer-0" aria-describedby="@lang($langfile . '.parent_layer_0')" autocomplete="off" required>
                                    <option value="" selected="selected" disabled>@lang($langfile . '.please_select')</option>
                                    <option value="0">@lang($langfile . '.add_new_layer_0')</option>
                                    @foreach ($tablelist as $menuLayer1)
                                        <option value="{{ $menuLayer1['menu_id'] }}" data-sub="{{json_encode($menuLayer1['sub'])}}">@lang($menuLayer1['menu_name'])</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- parent_layer_1 --}}
                        <div class="form-group row parent-menu-1">
                            <label for="addMenuParentLayer1" class="col-sm-3 col-form-label required">@lang($langfile . '.parent_layer_1')</label>
                            <div class="col-sm-9">
                                <select id="addMenuParentLayer1" name="parent_layer_1" class="form-control parent-layer-1" aria-describedby="@lang($langfile . '.parent_layer_1')"></select>
                            </div>
                        </div>
                        {{-- menu_name --}}
                        <div class="form-group row">
                            <label for="editMenuName" class="col-sm-3 col-form-label required">@lang($langfile . '.menu_name')</label>
                            <div class="col-sm-9">
                                <input type="text" id="editMenuName" name="menu_name" class="form-control" aria-describedby="@lang($langfile . '.menu_name')" value="" required/>
                                <input type="hidden" id="menuId" name="menu_id"/>
                            </div>
                        </div>
                        {{-- status --}}
                        <div class="form-group row">
                            <label for="status" class="col-sm-3 col-form-label required">Status</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="status" required>
                                    <option value="Y">@lang($langfile . '.menu_status_Y')</option>
                                    <option value="N">@lang($langfile . '.menu_status_N')</option>
                                </select>
                            </div>
                        </div>
                        {{-- menu_visible --}}
                        <div class="form-group row">
                            <label for="menu_visible" class="col-sm-3 col-form-label required">@lang($langfile . '.menu_visible')</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="menu_visible" required>
                                    <option value="Y">@lang($langfile . '.menu_visible_Y')</option>
                                    <option value="N">@lang($langfile . '.menu_visible_N')</option>
                                </select>
                            </div>
                        </div>
                        {{-- menu_url --}}
                        <div class="form-group row">
                            <label for="url" class="col-sm-3 col-form-label required">@lang($langfile . '.menu_url')</label>
                            <div class="col-sm-9">
                                <input type="text" id="menu_url" name="url" class="form-control" aria-describedby="@lang($langfile . '.menu_url')" autocomplete="off" required/>
                            </div>
                        </div>

                        <p class="text-muted my-0">@lang($langfile . '.After_updating_add_language')</p>
                        <input type="hidden" name="menu_layer">
                        <input type="hidden" name="parent_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var menuModal = new coreui.Modal(document.getElementById('menuModal'));

    </script>
@endsection
