@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Talepler</div>

                    @foreach ($demands as $item)
                        <div class="card-body">
                            <a href="{{ route('demand.show',['demand'=> $item->id]) }}" class="list-group-item">
                                {{ $item->customer->name }} - {{ $item->title }} <span>({{ $item->count }})</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
