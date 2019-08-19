<script type="text/javascript">
    $(document).ready(function () {
        var logs_table = $('#logs-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('home.index') }}",
            columns: [
                {data: null, defaultContent: '', orderable: false, sClass: "selector"},
                {data: 'name', name: 'name'},
                {data: 'surname', name: 'surname'},
                {data: 'type', name: 'type'},
                {data: 'status', name: 'status'},
                {data: 'reason', name: 'reason'},
                {data: 'created_at', name: 'created_at'}   
            ]
        });
    });
</script>