<div>
    <table id="groupTable" class="table table-striped group-table">
        <thead>
        <tr>
            <th scope="col">@lang($langfile . '.group_id')</th>
            <th scope="col">@lang($langfile . '.group_name')</th>
            <th scope="col">@lang($langfile . '.group_members')</th>
            <th scope="col">@lang($langfile . '.status')</th>
            <th scope="col">@lang($langfile . '.created_user')</th>
            <th scope="col">@lang($langfile . '.updated_user')</th>
            <th scope="col">@lang($langfile . '.created_at')</th>
            <th scope="col">@lang($langfile . '.updated_at')</th>
            <th scope="col">@lang($langfile . '.edit_menu')</th>
        </tr>
        </thead>
        <tbody>
        @if($tablelist)
            @foreach ($tablelist as $tr)
                <tr class="tr-vertical-align-middle">
                    <td class="group-id">{{ $tr->group_id }}</td>
                    <td>{{ $tr->group_name }}</td>
                    <td><button type="button" class="btn btn-secondary group-member">@lang($langfile . '.in_group_members')</button></td>
                    <td>
                        <div class="custom-control custom-switch status" data-status="{{ $tr->status }}" data-groupid="{{ $tr->group_id }}">
                            <input type="checkbox" class="custom-control-input status-input" id="customSwitch{{ $tr->group_id }}">
                            <label class="custom-control-label status-label" for="customSwitch{{ $tr->group_id }}"></label>
                        </div>
                    </td>
                    <td>{{ $tr->created_user }}</td>
                    <td>{{ $tr->updated_user }}</td>
                    <td>{{ $tr->created_at }}</td>
                    <td>{{ $tr->updated_at }}</td>
                    <td><i class="fas fa-edit mr-2 edit-group" data-groupid="{{ $tr->group_id }}"></i></td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>

{{-- confirm change status modal  --}}
<div id="statusConfirmModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang($langfile . '.status_will_be_changed')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>@lang($langfile . '.continue_or_not')</p>
                <form id="statusChangeForm">
                    @csrf
                    <input type="hidden" name="group_id" />
                    <input type="hidden" name="status" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="changeStatus" class="btn btn-primary">@lang($langfile . '.save')</button>
                <button type="button" id="cancelChange" class="btn btn-secondary" data-dismiss="modal">@lang($langfile . '.cancel')</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(function() {

        @if($tablelist)
        $.each($('.status'), function( k, v ) {
            var status = $(this).data('status');
            if(status == 'Y') {
                $(this).children('input').prop('checked', true);
                $(this).children('.status-label').text("@lang($langfile . '.group_status_Y')");
            } else {
                $(this).children('input').prop('checked', false);
                $(this).children('.status-label').text("@lang($langfile . '.group_status_N')");
            }
        });
        @endif

        $('.status-input').click(function(){
            var changeStatus = ($(this).prop('checked') == true) ? 'Y' : 'N';
            var changeStatusGroupId = $(this).parent('div').data('groupid');
            $('#statusChangeForm').find("input[name='group_id']").val(changeStatusGroupId);
            $('#statusChangeForm').find("input[name='status']").val(changeStatus);
            $('#statusConfirmModal').modal('show');
        });

        $('#changeStatus').click(function(){
            $.ajax({
                url: "{{ route('dashboard.group.management.update.status') }}",
                type: "POST",
                data: $('#statusChangeForm').serialize(),
                success: function (response) {
                    $('#statusConfirmModal').modal('hide');

                    var groupId = response.data.group_id;
                    var status = (response.data.status == 'Y') ? true : false;
                    var text = (response.data.status == 'Y') ? "@lang($langfile . '.group_status_Y')" : "@lang($langfile . '.group_status_N')";

                    $('#customSwitch'+groupId).prop('checked', status);
                    $('#customSwitch'+groupId).siblings('.status-label').text(text);
                },
                error: function(response) {
                    if(response.status == 422) {
                        alert("@lang($langfile . '.validation_fail')");
                    } else {
                        alert(response.status + ' ' + response.statusText);
                    }
                }
            });
        });

        $("#cancelChange").click(function(){
            var groupId = $('#statusChangeForm').find("input[name='group_id']").val();
            var changeStatus = $('#statusChangeForm').find("input[name='status']").val();

            if(changeStatus == 'N') {
                $('#customSwitch'+groupId).prop('checked', true);
                $('#customSwitch'+groupId).text("@lang($langfile . '.group_status_Y')");
            } else {
                $('#customSwitch'+groupId).prop('checked', false);
                $('#customSwitch'+groupId).text("@lang($langfile . '.group_status_N')");
            }
        });

        $('.group-member').click(function(){
            var groupId = $(this).parents('tr').find('.group-id').text();
            $.ajax({
                url: "{{ route('dashboard.group.management.get.member') }}",
                type: "GET",
                data: {
                    {{--_token: '{!! csrf_token() !!}',--}}
                    groupId: groupId
                },
                success: function (response) {
                    $('#groupMemberModal').modal('show');
                    $('.group-info .group-id').text(response.data[0].group_id);
                    $('.group-info .group-name').text(response.data[0].group_name);
                    $('.group-info .input-user').val('');
                    $('.input-group').val(response.data[0].group_id);
                    $('.group-member-table tbody tr').remove();

                    $.each(response.data, function( k, v ) {
                        if(v.email != null && v.name != null) {
                            var formatDate = new Date(v.created_at).toISOString().replace(/T/, ' ').replace(/\..+/, '');
                            $('.group-member-table tbody').append("<tr><td>" + v.email + "</td><td>" + v.name + "</td><td>" + formatDate + "</td><td><button class='btn btn-secondary remove-member' data-user-id='" + v.user_id + "' data-group-id='" + v.group_id + "'><i class='fas fa-trash'></i></button></td></tr>");
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
        });

        $('.edit-group').click(function(){
            var groupId = $(this).data('groupid');
            window.location = "{{ route('adminui.group.management.purview.index') }}?group_id=" + groupId;
        });

        $('#addGroup').click(function(){
            window.location = "{{ route('adminui.group.management.purview.index') }}";
        });
    });

</script>
