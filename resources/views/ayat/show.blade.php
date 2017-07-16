@extends('layouts.app')

@section('content')
    <div class = "jumbotron">
        @foreach($arabic_ayat as $ayat)
            <h1 align="center">{{$ayat->verse}}</h1>
        @endforeach
    </div>
    
    <h1 align= "center">{{ $indonesian_ayat }}</h1>
    
    <h2 align = "center">{{ $ayat_surat }}</h2>
    
@endsection