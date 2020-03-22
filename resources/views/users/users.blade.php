@extends('layouts.app')

@section('content')
    <users-component :access_toke="{{json_encode($token)}}"></users-component>
@endsection