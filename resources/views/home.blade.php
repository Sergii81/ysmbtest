@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <a href="{{ route('page_create_shipment', $token ?? '') }}" class="btn btn-primary" style="margin-bottom: 5px">{{__('Add new shipment')}}</a>

            <table id="table_transactions" class="table table-bordered table-striped table-condensed flip-content table-hover">
                <thead class="flip-content">
                <tr>
                    <th>{{__('id')}}</th>
                    <th>{{__('name')}}</th>
                    <th>{{__('is_send')}}</th>
                    <th>{{__('items')}}</th>
                    <th>{{__('Add items')}}</th>
                    <th>{{__('Delete Shipment Items')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($shipments as $shipment)
                    <tr>
                        <td>{{$shipment->id}}</td>
                        <td>{{$shipment->name}}</td>
                        <td>{{$shipment->is_send}}</td>
                        @if(empty($shipment->items))
                            <td>-</td>
                        @else
                            <td><a href="{{route('page_delete_item', $shipment->id ?? '')}}">{{__('items')}}</a></td>
                        @endif
                        <td>
                            <a href="{{ route('page_create_item', $shipment->id ?? '') }}" class="btn btn-success">{{__('Add items')}}</a>
                        </td>
                        <td>
                            @if(!empty($shipment->items))
                                <a href="{{ route('page_delete_item', $shipment->id ?? '') }}" class="btn btn-info">{{__('Delete Shipment Items')}}</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
