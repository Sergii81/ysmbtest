@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        {{ __('Edit Items') }}
                        <div>
                            <span>{{ __('Shipment ID ') }}{{$shipment->id}}</span>
                        </div>
                        <div>
                            <span>{{ __('Shipment Name ') }}{{$shipment->name}}</span>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('page_delete_item_post') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="shipment_id" value="{{ $shipment->id }}">

                            <table id="table_transactions" class="table table-bordered table-striped table-condensed flip-content table-hover">
                                <thead class="flip-content">
                                <tr>
                                    <th>{{__('id')}}</th>
                                    <th>{{__('code')}}</th>
                                    <th>{{__('Select')}}</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($shipment->items as $item)
                                    <tr>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->code}}</td>
                                        <td>
                                            <input type="checkbox" name="delete[{{$item->id}}]">
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <div class="form-group row mb-0">

                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-danger">
                                        {{ __('Delete') }}
                                    </button>
                                    <a href="{{ route('page_create_item', $shipment->id ?? '') }}" class="btn btn-success">{{__('Add items')}}</a>
                                    <a href="{{route('home')}}" class="btn btn-info" >{{ __('Back') }}</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection



