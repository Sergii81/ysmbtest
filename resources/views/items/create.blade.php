@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">{{ __('Create Items') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('page_create_item_post') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div id="items_list">
                                <div id="items" class="card-body">
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right">{{ __('Item ID') }}</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control @error('id') is-invalid @enderror" name="items[id][]" value="{{$id ?? ''}}" required autocomplete="item_id" autofocus>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label text-md-right">{{ __('Shipment ID') }}</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control @error('shipment_id') is-invalid @enderror" name="items[shipment_id][]" value="{{$shipment->id ?? ''}}" required autocomplete="shipment_id" autofocus>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label  class="col-md-4 col-form-label text-md-right">{{ __('Shipment Name') }}</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="items[name][]" value="{{$shipment->name ?? ''}}" required autocomplete="name" autofocus>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label  class="col-md-4 col-form-label text-md-right">{{ __('Item Code') }}</label>
                                        <div class="col-md-6">
                                            <input  type="text" class="form-control @error('code') is-invalid @enderror" name="items[code][]" value="" required autocomplete="item_code" autofocus>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">

                                <div class="col-md-6 offset-md-4">
                                    <input type="button"  class="btn btn-success"  onclick="addItem()" value="{{ __('Add item') }}">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Create') }}
                                    </button>
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

@section ('scripts')
    <script>
        let clicks = 0;
        function addItem() {
            clicks += 1;
            let parent = document.getElementById('items_list');
            let elem = parent.querySelector('#items');
            let clone = elem.cloneNode(true);
            let cloneValue = elem.children[0].getElementsByTagName("input")[0].value;
            clone.children[0].getElementsByTagName("input")[0].value = parseInt(cloneValue) + clicks;
            clone.children[3].getElementsByTagName("input")[0].value = '';
            parent.appendChild(clone);
        }
    </script>
@endsection

