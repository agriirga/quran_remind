@extends('layouts.master_mail')

@section('content')

    @foreach($arabic_ayat as $ayat)
        <h1>{{$ayat->verse}}</h1>
    @endforeach
    <br>
    <h1>{{ $indonesian_ayat }}</h1>
    <h2>{{ $ayat_surat }}</h2>
    
@endsection