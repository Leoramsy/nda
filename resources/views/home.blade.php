@extends('layouts.app')
@section('js_files')
@include('javascript.home')
@endsection
@section('content')
<div class="container">
    <div class="card" style="margin-top: 10px">
        <div class="card-header">Notification Logs</div>
        <div class="card-body">            
            <table id="logs-table" class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Notification Type</th>
                        <th>Status</th>
                        <th>Reason</th>
                        <th>Date Sent</th>                                     
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
