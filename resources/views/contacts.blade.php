@extends('layouts.app')
@section('js_files')
@include('javascript.contacts')
@endsection
@section('content')
<div class="container">
    <div class="card" style="margin-top: 10px">
        <div class="card-header">Contacts</div>
        <div class="card-body">        
            <table id="contacts-table" class="table table-bordered data-table" style="width: 100%">
                <thead>
                    <tr>
                        <th class='selector'>
                            
                        </th> 
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Province</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Opted In</th>                                             
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div id="contacts-editor" class="custom-editor">
                <fieldset class="half-set multi-set">
                    <legend><i class="fa fa-user" aria-hidden="true"></i> Contact:</legend>                             
                    <div class="col-md-12">
                        <editor-field name="name"></editor-field>
                        <editor-field name="surname"></editor-field>
                    </div>
                    <div class="col-md-12">
                        <editor-field name="mobile_number"></editor-field>
                        <editor-field name="email"></editor-field>
                    </div>
                    <div class="col-md-12">
                        <editor-field name="province_id"></editor-field>                        
                    </div>
                    <div class="col-md-12">
                        <editor-field name="notification_type"></editor-field>                        
                    </div>
                </fieldset>                            
            </div> 
        </div>
    </div>
</div>
@endsection
