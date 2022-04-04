<div>

@push('styles')
    <!-- daterange picker -->
        <link rel="stylesheet" href="{{URL(LbsConstants::BASE_ADMIN_ASSETS.'plugins/daterangepicker/daterangepicker.css')}}">
    @endpush

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">

                    <br>

                    <div class="row">

                        <div class="col-sm-6">
                            <div class="form-inline">

                                <div class="form-group input-group-sm">

                                    <select class="form-control select2 " style="width: 100%;" wire:model="searchable_col">
                                        @foreach($columns as $colKey => $column)
                                            <option value="{{$colKey}}" class="{{$colKey==false?'hide':''}}"> {{ \App\Helpers\PoHelper::NormalizeColString($colKey)  }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group input-group-sm">

                                    <select class="form-control select2 " style="width: 100%;" wire:model="searchable_operator">
                                        @foreach($operators as $operatorKey => $operator)
                                            <option value="{{$operatorKey}}"> {{ $operator }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="input-group input-group-sm" style="width: 250px;">
                                    <input type="text" name="table_search" class="form-control float-right"
                                           placeholder="Search" wire:model.debounce.500ms="searchable_col_val">

                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default" wire:click="search_reset">
                                            <i class="fas fa-sync"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="col-sm-2">
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-primary">
                                Select Columns
                            </button>

                        </div>

                    </div>



                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                        <tr>
                            @foreach($columns as $colKey => $column)
                            <th class="{{$column==false?'hide':''}}"> {{ \App\Helpers\PoHelper::NormalizeColString($colKey)  }}</th>
                            @endforeach
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($collections as $key => $collection)
                            <tr>
                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'tender_no' )==false?'hide':''}}" >{{$collection->tender_no}}</td>
                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'item_code' )==false?'hide':''}}" >{{$collection->item_code}}</td>
                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'vendor_code' )==false?'hide':''}}" >{{$collection->vendor_code}}</td>
                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'vendor_name' )==false?'hide':''}}" >{{$collection->vendor_name}}</td>
                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'Ordered_quantity' )==false?'hide':''}}" >{{$collection->Ordered_quantity}}</td>
                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'pending_qty' )==false?'hide':''}}" >{{$collection->pending_qty}}</td>
                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'total_recived_qty' )==false?'hide':''}}" >{{$collection->total_recived_qty}}</td>
                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'order_total' )==false?'hide':''}}" >{{$collection->order_total}}</td>
                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'gr_amount' )==false?'hide':''}}" >{{$collection->gr_amount}}</td>
                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'trade_date' )==false?'hide':''}}" >{{$collection->trade_date}}</td>
                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'storage_location' )==false?'hide':''}}" >{{$collection->storage_location}}</td>
                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'delivery_address' )==false?'hide':''}}" >{{$collection->delivery_address}}</td>
                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'contract_item_no' )==false?'hide':''}}" >{{$collection->contract_item_no}}</td>
                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'plant' )==false?'hide':''}}" >{{$collection->plant}}</td>
                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'generic_mat_code' )==false?'hide':''}}" >{{$collection->generic_mat_code}}</td>
                                <td><a href="{{route('web.route.po.MawTableLineItem',['slug'=>base64_encode($collection->item_code)])}}"><i class="fas fa-eye"></i></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                        {{$collections->links()}}
                    </ul>
                </div>

            </div>
            <!-- /.card -->
        </div>
    </div>



{{--    ==============  =====--}}


    <div class="modal fade" id="modal-primary"  wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content bg-secondary">
                <div class="modal-header">
                    <h4 class="modal-title">Select Columns</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">

                            @foreach($columns as $colKey => $column)
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id=" {{$colKey}}" {{$column==false?'':'checked'}} wire:model="columns.{{$colKey}}">
                                <label for="checkboxPrimary3">
                                    {{ \App\Helpers\PoHelper::NormalizeColString($colKey)  }}
                                </label>
                            </div> <br>
                            @endforeach


                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

{{--    ===================--}}


{{--    ============Extra Large Model=========--}}


    <div class="modal fade" id="modal-xl">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    @livewire('mail.compose-mail-component')
                </div>
                <div class="modal-footer justify-content-between">

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

{{--    <div class="modal fade" id="modal-xl"  wire:ignore.self>--}}
{{--        <div class="modal-dialog modal-xl">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header">--}}
{{--                    <h4 class="modal-title">Pos</h4>--}}
{{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                        <span aria-hidden="true">&times;</span>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--                <div class="modal-body table-responsive p-0">--}}
{{--                    <table class="table table-hover text-nowrap">--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            @foreach($columns as $colKey => $column)--}}
{{--                                <th class="{{$column==false?'hide':''}}"> {{ \App\Helpers\PoHelper::NormalizeColString($colKey)  }}</th>--}}
{{--                            @endforeach--}}

{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                        @if($childCollections)--}}
{{--                        @foreach($childCollections as $key => $childCollection)--}}
{{--                            <tr>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'item_code' )==false?'hide':''}}" >{{$childCollection->item_code}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'desc' )==false?'hide':''}}" >{{$childCollection->desc}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'region_qtye' )==false?'hide':''}}" >{{$childCollection->region_qtye}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'recived' )==false?'hide':''}}" >{{$childCollection->recived}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'initial_recived' )==false?'hide':''}}" >{{$childCollection->initial_recived}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'unit_price' )==false?'hide':''}}" >{{$childCollection->unit_price}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'pending_qty' )==false?'hide':''}}" >{{$childCollection->pending_qty}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'total_recived_qty' )==false?'hide':''}}" >{{$childCollection->total_recived_qty}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'initial_reciving_value' )==false?'hide':''}}" >{{$childCollection->initial_reciving_value}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'item_total_value' )==false?'hide':''}}" >{{$childCollection->item_total_value}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'final_reciving_value' )==false?'hide':''}}" >{{$childCollection->final_reciving_value}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'value_of_delivered' )==false?'hide':''}}" >{{$childCollection->value_of_delivered}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'available_qty_for_main_store' )==false?'hide':''}}" >{{$childCollection->available_qty_for_main_store}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'available_qty_for_all_locations' )==false?'hide':''}}" >{{$childCollection->available_qty_for_all_locations}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'monthly_consumption' )==false?'hide':''}}" >{{$childCollection->monthly_consumption}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'tender_no' )==false?'hide':''}}" >{{$childCollection->tender_no}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'contract_no' )==false?'hide':''}}" >{{$childCollection->contract_no}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'tender_name' )==false?'hide':''}}" >{{$childCollection->tender_name}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'vendor_number' )==false?'hide':''}}" >{{$childCollection->vendor_number}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'vendor_name' )==false?'hide':''}}" >{{$childCollection->vendor_name}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'country_of_origion' )==false?'hide':''}}" >{{$childCollection->country_of_origion}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'manfacturing_co' )==false?'hide':''}}" >{{$childCollection->manfacturing_co}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'contract_start_date' )==false?'hide':''}}" >{{$childCollection->contract_start_date}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'contract_start_hijri' )==false?'hide':''}}" >{{$childCollection->contract_start_hijri}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'contract_end_date' )==false?'hide':''}}" >{{$childCollection->contract_end_date}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'contract_end_date_hijri' )==false?'hide':''}}" >{{$childCollection->contract_end_date_hijri}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'region_code' )==false?'hide':''}}" >{{$childCollection->region_code}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'store' )==false?'hide':''}}" >{{$childCollection->store}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'shipments' )==false?'hide':''}}" >{{$childCollection->shipments}}</td>--}}
{{--                                <td  class="{{\Illuminate\Support\Arr::get($columns, 'trade_date' )==false?'hide':''}}" >{{$childCollection->trade_date}}</td>--}}

{{--                            </tr>--}}
{{--                        @endforeach--}}
{{--                        @endif--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}
{{--                <div class="modal-footer justify-content-between">--}}
{{--                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <!-- /.modal-content -->--}}
{{--        </div>--}}
{{--        <!-- /.modal-dialog -->--}}
{{--    </div>--}}
    <!-- /.modal -->
{{--    =====================--}}

@push('scripts')
    <!-- date-range-picker -->
        <script src="{{URL(LbsConstants::BASE_ADMIN_ASSETS.'plugins/daterangepicker/daterangepicker.js')}}"></script>

        <script>
          $(document).ready(function () {
              $('#reservation').daterangepicker()
          })
        </script>
    @endpush
</div>
