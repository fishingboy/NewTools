@extends('adminui::layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center full-card">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h1>
                            @lang($langfile . '.title')
                        </h1>
                    </div>
                    <div class="card-body min-card">
                        <form>
                            @csrf
                            <div class="select-group mb-3">
                                <button type="button" class="btn btn-info" id="addGroup" name="add_group">@lang($langfile . '.add_group')<!--新增群組--></button>
                            </div>
                        </form>
                        <div class="componentDiv overflow-scroll">
                            @component(
                                    'adminui::purview.edit',
                                    ['menuList' => $menulist, 'tablelist' => $tablelist, 'pagename' => $pagename, 'langfile' => $langfile]
                                )
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Group Member Modal --}}
    <div class="modal fade" id="groupMemberModal" tabindex="-1" role="dialog" aria-labelledby="groupMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="groupMemberModalLabel">@lang($langfile . '.group_members')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container group-info">
                        <div class="row">
                            <div class="col-3">
                                @lang($langfile . '.group_id')
                            </div>
                            <div class="col group-id">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-3">
                                @lang($langfile . '.group_name')
                            </div>
                            <div class="col group-name">
                            </div>
                        </div>
                        <div class="row mt-3 mb-5">
                            <div class="col-3 align-middle">
                                @lang($langfile . '.enter_name_label')
                            </div>
                            <div class="col mt-1 mb-3 input-group">
                                <input type="hidden" id="inputGroup" class="input-group" name="input_group" />
                                <input type="text" id="inputUser" name="input_user" class="form-control input-user" placeholder="@lang($langfile . '.enter_name_placeholder')" aria-describedby="@lang($langfile . '.enter_name_placeholder')" />
                                <div class="input-group-append">
                                    <button class="btn btn-info" type="button" id="addMemberBtn">@lang($langfile . '.add')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped group-member-table">
                        <thead>
                        <tr>
                            <th scope="col">@lang($langfile . '.email')</th>
                            <th scope="col">@lang($langfile . '.name')</th>
                            <th scope="col">@lang($langfile . '.created_at')</th>
                            <th scope="col">@lang($langfile . '.remove')</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // (function() {
        //     'use strict';
        //     window.addEventListener('load', function() {
        //         var forms = document.getElementsByClassName('needs-validation');
        //         var validation = Array.prototype.filter.call(forms, function(form) {
        //             form.addEventListener('submit', function(event) {
        //                 if (form.checkValidity() === false) {
        //                     event.preventDefault();
        //                     event.stopPropagation();
        //                 }
        //                 form.classList.add('was-validated');
        //             }, false);
        //         });
        //     }, false);
        // })();

        $.ajaxSetup({
            headers:
                {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        $(document).on('click', '#addMemberBtn', function(){
            var user = $(this).parents('.input-group').find('#inputUser').val();
            var groupId = $(this).parents('.input-group').find('#inputGroup').val();

            if(user == ''){
                alert("@lang($langfile . '.please_enter_user_name')");
                return;
            }

            $.ajax({
                url: "{{route('dashboard.group.management.add.member')}}",
                type: "POST",
                data: {
                    user: user,
                    groupId: groupId,
                },
                success: function (response) {
                    if (response.code == 200){
                        alert("@lang($langfile . '.user_has_been_in_this_group')");
                    } else {
                        alert("@lang($langfile . '.add_user_group_success')");

                        $('.group-member-table tbody tr').remove();
                        $.each(response.data, function( k, v ) {
                            var formatDate = new Date(v.created_at).toISOString().replace(/T/, ' ').replace(/\..+/, '');
                            $('.group-member-table tbody').append("<tr><td>" + v.email + "</td><td>" + v.name + "</td><td>" + formatDate + "</td><td><button class='btn btn-secondary remove-member' data-user-id='" + v.user_id + "' data-group-id='" + v.group_id + "'><i class='fas fa-trash'></i></button></td></tr>");
                        });
                    }
                },
                error: function(response) {
                    if(response.status == 404){
                        alert("@lang($langfile . '.user_not_found')");
                    } else if (response.status == 422) {
                        alert("@lang($langfile . '.validation_fail')");
                    } else {
                        alert(response.status + ' ' + response.statusText);
                    }
                }
            });
        });

        $(document).on('click', '.remove-member', removeMember);

        function removeMember(){
            var userId = $(this).data('user-id');
            var groupId = $(this).data('group-id');

            $.ajax({
                url: "{{ route('dashboard.group.management.remove.member') }}",
                type: "POST",
                data: {
                    groupId: groupId,
                    userId: userId
                },
                success: function (response) {
                    alert("@lang($langfile . '.remove_success')");
                    $('.group-member-table tbody tr').remove();
                    $.each(response.data, function( k, v ) {
                        if(v.email != null && v.name != null) {
                            var formatDate = new Date(v.created_at).toISOString().replace(/T/, ' ').replace(/\..+/, '');
                            $('.group-member-table tbody').append("<tr><td>" + v.email + "</td><td>" + v.name + "</td><td>" + formatDate + "</td><td><button class='btn btn-secondary remove-member' data-user-id='" + v.user_id + "' data-groupid='" + v.group_id + "'><i class='fas fa-trash'></i></button></td></tr>");
                        }
                    });
                },
                error: function(response) {
                    if(response.status == 422) {
                        alert("@lang($langfile . '.validation_fail')");
                    } else {
                        alert(response.status + ' ' + response.statusText);
                    }
                }
            });
        }
    </script>
@endsection



