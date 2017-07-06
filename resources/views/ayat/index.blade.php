@extends('layouts.app')

@section('content')

    <h1>{{ $complete_ayat }}</h1>
    <br>
    @foreach($arabic_ayat as $ayat)
        <h1>{{$ayat->verse}}</h1>
    @endforeach
    <h2>{{ $ayat_surat }}</h2>
    
@endsection