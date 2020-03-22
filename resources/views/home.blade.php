@extends('layouts.app')

@section('content')

<home-component :user="{{json_encode($user)}}"></home-component>

@endsection
