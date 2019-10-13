@extends('layouts.base')

@section('content')
    <div class="container text-center">
        <img src="https://q2.qlogo.cn/headimg_dl?dst_uin=593198779&spec=100" alt="查价喵" class="rounded-circle mt-5" style="display: none;" id="qq-avatar">
        <!-- 提示 -->
        <div class="alert alert-warning alert-dismissible fade show mt-4" role="alert">
            在下面输入商品网址，然后点击<strong>开始查询</strong>，我会给你画一个折线图，并告诉你商品的历史价格。
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <!-- end 提示 -->
        <!-- 输入框 -->
        <div class="input-group mt-12 mt-4">
            <textarea class="form-control" rows="8" id="productUrl"></textarea>
        </div>
        <!-- end 输入框 -->
        <!-- 提交 -->
        <button type="button" class="btn btn-outline-secondary btn-block mt-3" role="button" id="clear">清空</button>
        <button type="button" class="btn btn-outline-primary btn-block mt-2" role="button" id="inquire">开始查询</button>
        <!-- end 提交 -->
    </div>

    <div class="container-fluid mt-5 pl-0 pr-0">
        <!-- 折线图 -->
        <div id="line-chart"{{-- style="height: 533px;"--}}></div>
        <!-- end 折线图 -->
    </div>
    <div class="container">
        <button type="button" class="btn btn-outline-danger btn-sm" id="idea">勾搭</button>
    </div>
@endsection

@push('js')
    <script src="/js/echarts.min.js"></script>
    <script src="/js/drawChart.js?20181124"></script>
    <script src="/js/main.js?20181124"></script>
@endpush
