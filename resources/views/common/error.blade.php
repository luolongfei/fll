@extends('layouts.base')

@section('title')
    出错啦
@endsection

@section('header')
@endsection

@section('content')
    <div class="container">
        <div class="raw">
            <div class="col-12 mt-5 pt-5">
                <div class="alert alert-warning show text-center display-4" role="alert">
                    {{$errorMsg}}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
@endsection
