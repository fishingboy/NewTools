<?php

?>
@extends('adminui::layouts.app')
@section('content')

    <div class="container-fluid">
        <div class="row justify-content-center full-card">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header">
                        <h2>
                            銷售據點
                        </h2>
                    </div>
                    <div class="card-body min-card">

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">Form Elements</div>
                                    <div class="card-body">
                                        <form class="form-horizontal">
                                            <div class="form-group">
                                                <label class="col-form-label" for="prependedInput">Prepended text</label>
                                                <div class="controls">
                                                    <div class="input-prepend input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text">@</span></div>
                                                        <input class="form-control" id="prependedInput" size="16" type="text">
                                                    </div>
                                                    <p class="help-block">Here's some help text</p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label" for="appendedInput">Appended text</label>
                                                <div class="controls">
                                                    <div class="input-group">
                                                        <input class="form-control" id="appendedInput" size="16" type="text">
                                                        <div class="input-group-append"><span class="input-group-text">.00</span></div>
                                                    </div><span class="help-block">Here's more help text</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label" for="appendedPrependedInput">Append and prepend</label>
                                                <div class="controls">
                                                    <div class="input-prepend input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                                        <input class="form-control" id="appendedPrependedInput" size="16" type="text">
                                                        <div class="input-group-append"><span class="input-group-text">.00</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label" for="appendedInputButton">Append with button</label>
                                                <div class="controls">
                                                    <div class="input-group">
                                                        <input class="form-control" id="appendedInputButton" size="16" type="text"><span class="input-group-append">
<button class="btn btn-secondary" type="button">Go!</button></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label" for="appendedInputButtons">Two-button append</label>
                                                <div class="controls">
                                                    <div class="input-group">
                                                        <input class="form-control" id="appendedInputButtons" size="16" type="text"><span class="input-group-append">
<button class="btn btn-secondary" type="button">Search</button>
<button class="btn btn-secondary" type="button">Options</button></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <button class="btn btn-primary" type="submit">Save changes</button>
                                                <button class="btn btn-secondary" type="button">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
