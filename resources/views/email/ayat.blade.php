@extends('layouts.master_mail')

@section('content')
    <div class = "jumbotron">
    @foreach($arabic_ayat as $ayat)
        <h1>{{$ayat->verse}}</h1>
    @endforeach
    </div>
    <br>
    <h2>{{ $indonesian_ayat }}</h2>
    <h3>{{ $ayat_surat }}</h3>
    
@endsection