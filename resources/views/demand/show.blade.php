@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('alert'))
            <div class="alert alert-{{ session('alert-style') }}">{{ session('alert') }}</div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if ($data->status == \App\Models\Demand::OPEN)
                    <a href="{{ route('demand.status', ['demandId' => $data->id, 'statusId' => \App\Models\Demand::CLOSED]) }}"
                        class="btn btn-danger mb-2">Talebi Kapat</a>
                @else
                    <a href="{{ route('demand.status', ['demandId' => $data->id, 'statusId' => \App\Models\Demand::OPEN]) }}"
                        class="btn btn-success mb-2">Talebi Aç</a>
                @endif
                <div class="card">
                    <div class="card-header">{{ $data->title }}</div>
                    <div class="card-body">
                        Talep Açan : {{ $data->customer->name }} <br>
                        Talep Durumu : <b>{{ $data->statusText }}</b><br>
                        Talep Tarih : {{ $data->date }}
                        <hr>
                        {{ $data->text }}
                    </div>
                </div>

                @if ($data->status == \App\Models\Demand::OPEN)
                    <div class="card mt-2">
                        <div class="card-header">Cevap Yaz</div>
                        <div class="card-body">
                            <form action=" {{ route('demand.store') }} " method="POST">
                                @csrf
                                <input type="hidden" name="demandId" value="{{ $data->id }}">
                                <textarea name="text" cols="30" rows="5" class="form-control mb-2"></textarea>
                                <button class="btn btn-success">Gönder</button>
                            </form>
                        </div>
                    </div>
                @endif
                <div class="mt-2">
                    @foreach ($messages as $item)
                        <li class="list-group-item">
                            {{ $item->user->name }}<br>
                            {{ $item->text }}
                        </li>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
