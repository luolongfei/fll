@extends('layouts.base')

@section('header')
    {{--此处为了覆盖默认的header--}}
@endsection

@section('content')
    <div class="container-fluid mt-5 pl-0 pr-0">
        <!-- 折线图 -->
        <div id="line-chart" style="height: 533px;"></div>
        <!-- end 折线图 -->
    </div>
@endsection

@section('footer')
    {{--此处为了覆盖默认的footer--}}
@endsection

@push('js')
    <script src="/js/echarts.min.js"></script>
    <script src="/js/drawChart.js?20181124"></script>
    <script src="/js/main.js?20181124"></script>
@endpush
