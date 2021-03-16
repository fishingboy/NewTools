<div class="">
    <table id="mytable" class="table table-striped table-outline">
        <thead>
        <tr>
            @if(isset($tablelist['headers']))
                @foreach ($tablelist['headers'] as $header)
                <td>
                    {{ $header }}
                </td>
                @endforeach
                <td>
                    Action
                </td>
            @endif
        </tr>
        </thead>
        <tbody>
        @if(isset($tablelist['data']))
            @foreach ($tablelist['data'] as $row)

                <tr>
                    @foreach ($row as $column => $th)
                        @if(isset($tablelist['headers'][$column]))
                        <td>
                            {{ $th }}
                        </td>
                        @endif
                    @endforeach

                        <td>
                            <button class="btn btn-sm btn-primary" type="button" action_id="{{$row['id']}}" >perview</button>
                            <button class="btn btn-sm btn-success" type="button" action_id="{{$row['id']}}">edit</button>
                            <button class="btn btn-sm btn-danger" type="button" action_id="{{$row['id']}}">delete</button>
                        </td>

                </tr>

            @endforeach
        @endif
        </tbody>
    </table>

    {{ $slot }}

</div>
<script>
</script>
