@extends('layouts.app')
@section('title', __('lang.stores'))
@section('breadcrumbbar')
    <div class="breadcrumbbar">
        <div class="row align-items-center">
            <div class="col-md-8 col-lg-8">
                <h4 class="page-title">@lang('lang.stores')</h4>
                <div class="breadcrumb-list">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/')}}">@lang('lang.dashboard')</a></li>
                        <li class="breadcrumb-item"><a href="#">@lang('lang.settings')</a></li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('lang.stores')</li>
                    </ol>
                </div>
            </div>
            <div class="col-md-4 col-lg-4">
                <div class="widgetbar">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".add-store" href="{{route('admin.store.create')}}">@lang('lang.add_store')</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')

    <!-- Start Contentbar -->
    <div class="contentbar">
        <!-- Start row -->
        <div class="row">
            <!-- Start col -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">@lang('lang.stores')</h5>
                    </div>
                    <br/>
                    {{-- +++++++++++++++ Style : checkboxes and labels inside selectbox +++++++++++++++ --}}
                    <style>
                        .selectBox {
                        position: relative;
                        }

                        /* selectbox style */
                        .selectBox select
                        {
                            width: 100%;
                            padding: 0 !important;
                            padding-left: 4px;
                            padding-right: 4px;
                            color: #fff;
                            border: 1px solid #596fd7;
                            background-color: #596fd7;
                            height: 39px !important;
                        }

                        .overSelect {
                        position: absolute;
                        left: 0;
                        right: 0;
                        top: 0;
                        bottom: 0;
                        }

                        #checkboxes {
                        display: none;
                        border: 1px #dadada solid;
                        height: 125px;
                        overflow: auto;
                        padding-top: 10px;
                        /* text-align: end;  */
                        }

                        #checkboxes label {
                        display: block;
                        padding: 5px;

                        }

                        #checkboxes label:hover {
                        background-color: #ddd;
                        }
                        #checkboxes label span
                        {
                            font-weight: normal;
                        }
                    </style>
                    {{-- ++++++++++++++++++ Show/Hide Table Columns : selectbox of checkboxes ++++++++++++++++++ --}}
                    <div class="col-md-4 col-lg-4">
                        <div class="multiselect col-md-6">
                            <div class="selectBox" onclick="showCheckboxes()">
                                <select class="form-select form-control form-control-lg">
                                    <option>@lang('lang.show_hide_columns')</option>
                                </select>
                                <div class="overSelect"></div>
                            </div>
                            <div id="checkboxes">
                                {{-- +++++++++++++++++ checkbox1 : name +++++++++++++++++ --}}
                                <label for="col1_id">
                                    <input type="checkbox" id="col1_id" name="col1" checked="checked" />
                                    <span>@lang('lang.name')</span> &nbsp;
                                </label>
                                {{-- +++++++++++++++++ checkbox2 : branch +++++++++++++++++ --}}
                                <label for="col2_id">
                                    <input type="checkbox" id="col2_id" name="col2" checked="checked" />
                                    <span>@lang('lang.branch')</span>
                                </label>
                                {{-- +++++++++++++++++ checkbox3 : phone_number +++++++++++++++++ --}}
                                <label for="col3_id">
                                    <input type="checkbox" id="col3_id" name="col3" checked="checked" />
                                    <span>@lang('lang.phone_number')</span>
                                </label>
                                {{-- +++++++++++++++++ checkbox4 : email +++++++++++++++++ --}}
                                <label for="col4_id">
                                    <input type="checkbox" id="col4_id" name="col4" checked="checked" />
                                    <span>@lang('lang.email')</span>
                                </label>
                                {{-- +++++++++++++++++ checkbox5 : manager_name +++++++++++++++++ --}}
                                <label for="col5_id">
                                    <input type="checkbox" id="col5_id" name="col5" checked="checked" />
                                    <span>@lang('lang.manager_name')</span>
                                </label>
                                {{-- +++++++++++++++++ checkbox6 : manager_mobile_number +++++++++++++++++ --}}
                                <label for="col6_id">
                                    <input type="checkbox" id="col6_id" name="col6" checked="checked" />
                                    <span>@lang('lang.manager_mobile_number')</span>
                                </label>
                                {{-- +++++++++++++++++ checkbox7 : action +++++++++++++++++ --}}
                                <label for="col7_id">
                                    <input type="checkbox" id="col7_id" name="col7" checked="checked" />
                                    <span>@lang('lang.action')</span>
                                </label>
                            </div>
                        </div>
                    </div> <br/>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="container-fluid">
                                    <form action="{{route('admin.store.index')}}" method="get" id="filters_form">
                                        <div class="row">
                                            {{-- ++++++++++++++++++++ branches filter ++++++++++++++++++++ --}}
                                            <div class="col-2">
                                                <div class="form-group">
                                                    {!! Form::label('branch_id' ,__('lang.branch')) !!}
                                                    {!! Form::select('branch_id',$branches,request()->branch_id,
                                                        ['class' => 'form-control select2 store','placeholder'=>__('lang.please_select'), 'id' => 'branch_id']
                                                    ) !!}
                                                </div>
                                            </div>
                                            {{-- ++++++++++++++++++++ stores filter ++++++++++++++++++++ --}}

                                            <div class="col-2 mt-4">
                                                <div class="form-group">
                                                    <button type="submit" name="submit" class="btn btn-primary" title="search">
                                                        <i class="fa fa-eye"></i> {{ __('lang.filter') }}
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-2 mt-4">
                                                <a href="{{route('admin.store.index')}}" class="btn btn-danger">@lang('lang.clear_filters')</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <table id="datatable-buttons" class="table dataTable">
                            <thead>
                                <tr>
                                    <th class="col1">@lang('lang.name')</th>
                                    <th class="col2">@lang('lang.branch')</th>
                                    <th class="col3">@lang('lang.phone_number')</th>
                                    <th class="col4">@lang('lang.email')</th>
                                    <th class="col5">@lang('lang.manager_name')</th>
                                    <th class="col6">@lang('lang.manager_mobile_number')</th>
                                    <th class="col7 notexport">@lang('lang.action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($stores as $store)
                                <tr>
                                    <td class="col1">{{$store->name}}</td>
                                    <td class="col2">{{!empty($store->branch) ? $store->branch->name : ''}}</td>
                                    <td class="col3">{{$store->phone_number}}</td>
                                    <td class="col4">{{$store->email}}</td>
                                    <td class="col5">{{$store->manager_name}}</td>
                                    <td class="col6">{{$store->manager_mobile_number}}</td>
                                    <td class="col7 no-print">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">خيارات                                            <span class="caret"></span></button>
                                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu" x-placement="bottom-end" style="position: absolute; transform: translate3d(73px, 31px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <li>
                                                    <a data-href="{{route('admin.store.edit', $store->id)}}"
                                                       data-container=".view_modal" class="btn btn-modal"><i
                                                            class="dripicons-document-edit"></i> @lang('lang.edit')</a>                                                </li>
                                                <li class="divider"></li>
                                                <li>
                                                    <a data-href="{{route('admin.store.destroy', $store->id)}}"
                                                       class="btn delete_item text-red delete_item"><i
                                                            class="fa fa-trash"></i>
                                                        @lang('lang.delete')</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
     @include('store.create')
@endsection
<div class="view_modal no-print" ></div>
@push('javascripts')
    <script>
        // +++++++++++++++++++++++++++++++++ branches and stores filter +++++++++++++++++++++++++++++++++
        $('#branch_id').change(function(event) {
            var idBranch = this.value;
            // alert(idSubcategory1);
            $('#store_id').html('');
                $.ajax({
                    url: "/api/fetch_branch_stores",
                    type: 'POST',
                    dataType: 'json',
                    data: {branch_id: idBranch,_token:"{{ csrf_token() }}"},
                    success:function(response)
                    {
                        console.log(response);
                        $('#store_id').html('<option value="">{{ __("lang.store") }}</option>');
                        // $.each(response.branch_id,function(index, val)
                        $.each(response.store_id,function(index, val)
                        {
                            console.log("id = "+val.id ,"value = "+val.name);
                            $('#store_id').append('<option value="'+val.id+'">'+val.name+'</option>')
                        });
                    },
                    error: function (error)
                    {
                        console.error("Error fetching filtered stores:", error);
                    }

                })
        });
    </script>
     {{-- +++++++++++++++ Show/Hide checkboxes +++++++++++++++ --}}
     <script>
        // +++++++++++++++++ Checkboxs and label inside selectbox ++++++++++++++
        $("input:checkbox:not(:checked)").each(function() {
            var column = "table ." + $(this).attr("name");
            $(column).hide();
        });

        $("input:checkbox").click(function(){
            var column = "table ." + $(this).attr("name");
            $(column).toggle();
        });
        // +++++++++++++++++ Checkboxs and label inside selectbox : showCheckboxes() method ++++++++++++++
        var expanded = false;
        function showCheckboxes()
        {
            var checkboxes = document.getElementById("checkboxes");
            if (!expanded) {
                checkboxes.style.display = "block";
                expanded = true;
            } else {
                checkboxes.style.display = "none";
                expanded = false;
            }
        }
    </script>
@endpush
