<?php

?>
@extends('adminui::layouts.app')
@section('content')

    <div class="container-fluid">
        <div class="row justify-content-center full-card">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h2>
                                    衛浴小百科
                                </h2>

                            </div>
                            <div class="btn-toolbar d-none d-md-block" role="toolbar" aria-label="Toolbar with button groups">
                                <div class="btn-group">
                                    <button class="btn btn-primary" type="button">
                                        +NEW
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body min-card">

                        <div class="card-body min-card">
                            <div class="componentDiv">
                                @component('adminui::components.table', ['menuList' => $menulist, 'tablelist' => $tablelist, 'langfile' => [], 'pagename' => []])
                                @endcomponent
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
