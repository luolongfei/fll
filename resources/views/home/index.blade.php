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
        <button type="button" class="btn btn-outline-primary btn-block mt-2" role="button" id="start">开始查询</button>
        <!-- end 提交 -->
    </div>

    <div class="container-fluid mt-5 pl-0 pr-0">
        <!-- 折线图 -->
        <div id="line-chart"{{-- style="height: 533px;"--}}></div>
        <!-- end 折线图 -->
    </div>
    <div class="container">
        {{--<div class="alert alert-light highlight" id="tips">
            聪明网购不被坑，查询商品近半年的价格走势。目前支持
            <a href="https://www.jd.com/" target="_blank">京东</a>、<a href="https://www.tmall.com/" target="_blank">天猫</a>、<a
                    href="https://www.taobao.com/" target="_blank">淘宝</a>、<a href="https://www.amazon.cn/"
                                                                             target="_blank">亚马逊</a>、<a
                    href="http://www.dangdang.com/" target="_blank">当当网</a>。
            最近“日理万机”的我，并没时间仔细调程序，可能有很多细节问题，但是基础查询功能是通了的，先将就用到，后面慢慢优化。
            这里放出的beta版，供各位测试之用，
            如果你有什么好的的想法，可以狂按：
            <button type="button" class="btn btn-outline-danger btn-sm" id="idea">我有一个大胆的想法</button>
            ，
            说不定你想象的功能能实现。<i class="far fa-laugh-squint" style="font-size: 26px"></i>
        </div>--}}
        <button type="button" class="btn btn-outline-danger btn-sm" id="idea">勾搭作者</button>
    </div>
@endsection

@push('js')
    <script src="/js/echarts.min.js"></script>
    <script src="/js/drawChart.js?20181124"></script>
    <script src="/js/main.js?20181124"></script>
@endpush