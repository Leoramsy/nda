
<script type="text/javascript" src="{{ asset('packages/vue-2.5.13/vue.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/jQuery-3.2.1/jquery-3.2.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/bootstrap-4.0.0/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/bootstrap-4.0.0/assets/js/vendor/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/Select2-4.0.5/js/select2.min.js') }}"></script>        
<script type="text/javascript" src="{{ asset('packages/DataTables-1.10.16/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/Editor-1.7.2/js/dataTables.editor.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/Editor-1.7.2/js/editor.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/RowGroup-1.0.2/js/dataTables.rowGroup.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/Buttons-1.5.1/js/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/Buttons-1.5.1/js/buttons.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/FieldType-Select2-1.6.2/editor.select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/JSZip-2.5.0/jszip.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/Select-1.2.5/js/dataTables.select.min.js') }}"></script>        
<script type="text/javascript" src="{{ asset('js/main.js') }}"></script>
{{-- Add page specific scripts --}}
@yield('js_files')