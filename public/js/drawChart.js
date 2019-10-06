/* 绘制echarts统计图 by llf */

function drawChart() {
    return {
        myChart: null,
        eConfig: '',
        pieOption: { // 饼状图
            title: {
                text: '画个饼状图',
                subtext: '小冷君可以说是土豪了~',
                x: 'center'
            },
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c}元 ({d}%)"
            },
            legend: {
                orient: 'vertical',
                left: 'left',
                data: []
            },
            series: [
                {
                    name: '花钱记录',
                    type: 'pie',
                    radius: '55%',
                    center: ['50%', '60%'],
                    data: [],
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    },
                    roseType: false, // 展示成南丁格尔图 'radius' 扇区圆心角展现数据的百分比，半径展现数据的大小。'area' 所有扇区圆心角相同，仅通过半径展现数据大小。
                    labelLine: {
                        smooth: false,
                        lineStyle: {
                            width: 1
                        }
                    },
                }
            ],
        },
        lineOption: { // 折线图
            title: {
                text: '价格走势',
                subtext: '这是该商品最近半年的价格',
                x: 'center',
                top: 6,

            },
            tooltip: {
                trigger: 'item', // 注意：series.data.tooltip 仅在 tooltip.trigger 为 'item' 时有效
                // formatter: '',
                alwaysShowContent: true,
            },
            /*legend: { // 图例组件
                type: 'plain', // 普通图例，如果多则用滚动图例
                orient: 'vertical', // 图例列表的布局朝向
                left: 'left',
            },*/
            grid: { // 控制图表四方边距，以及高度。必须调试此项，否则可能遮挡把手
                left: 'left',
                right: '2%',
                bottom: 0,
                top: 100,
                height: 366, // dom高度533，grid高度366，这个配置下可以正常显示把手
                containLabel: true, // grid 区域是否包含坐标轴的刻度标签。防止标签溢出，显示不全
                show: true, // 是否显示直角坐标系网格
            },
            xAxis: {
                type: 'time',
                // maxInterval: 3600 * 24 * 1000, // 设置成 3600 * 24 * 1000 保证坐标轴分割刻度最大为一天
                boundaryGap: false,
                // data: [],
                axisPointer: {
                    // value: '2011-09-01', // 当前的 value。在使用 axisPointer.handle 时，可以设置此值进行初始值设定，从而决定 axisPointer 的初始位置
                    snap: true, // 坐标轴指示器是否自动吸附到点上，默认自动判断
                    show: true,
                    type: 'line',
                    z: 2, // z-index
                    lineStyle: {
                        color: '#c23531',
                        opacity: 0.5,
                        width: 2
                    },
                    label: { // 坐标轴指示器的文本标签
                        show: true, // 是否显示文本标签
                        formatter: function (params) {
                            return echarts.format.formatTime('yyyy-MM-dd', params.value);
                        },
                        backgroundColor: '#c23531',
                        borderColor: '#c23531',
                        color: '#fff',
                        fontWeight: 666,
                    },
                    handle: { // 手柄
                        show: false,
                        color: '#c23531',
                        // size: [200, 300],
                        borderWidth: 6,
                        // icon: 'image://https://q2.qlogo.cn/headimg_dl?dst_uin=593198779&spec=100',
                        margin: 50,
                    },
                },
                splitNumber: 6,
                minInterval: 1, // 自动计算的坐标轴最小间隔大小，设置成1保证坐标轴分割刻度显示成整数
            },
            yAxis: {
                type: 'value',
                axisLabel: { // 坐标轴刻度标签的相关设置
                    // formatter: '{value} 元',
                    showMinLabel: false, // 是否显示y轴最小标签
                },
                min: function (value) { // y轴最小刻度
                    return value.min * 0.99;
                },
                splitNumber: 4,
                minInterval: 1,
                axisLine: { // 坐标轴轴线相关设置
                    onZero: false,
                },
            },
            series: [
                {
                    name: '价格',
                    type: 'line',
                    data: [],
                    showSymbol: false, // 是否显示symbol，如果false则只有在tooltip hover的时候显示
                    symbol: 'circle', // 每个数据点处的标记，默认空心圆
                    /*markPoint: {
                        data: [
                            {type: 'max', name: '最大值'},
                            {type: 'min', name: '最小值'}
                        ]
                    },*/
                    /*markLine: {
                        data: [
                            {type: 'average', name: '平均值'},
                            [{
                                symbol: 'none',
                                x: '90%',
                                yAxis: 'max'
                            }, {
                                symbol: 'circle',
                                label: {
                                    normal: {
                                        position: 'start',
                                        formatter: '最大值哦'
                                    }
                                },
                                type: 'max',
                                name: '最高点'
                            }]
                        ]
                    },*/
                }
            ],
        },
        initChart: function (id) {
            this.myChart = echarts.init(document.getElementById(id));
            this.eConfig = echarts.config;
        },
        showPie: function (data) {
            let option = this.pieOption;
            let legendData = [];
            for (let i = 0; i < data.length; i++) {
                legendData.push(data[i].name);
            }

            option.legend.data = legendData;
            option.series[0].data = data;
            this.myChart.setOption(option);
        },
        showLine: function (result) {
            let price_data = result.price_data.info;
            let option = this.lineOption;
            let lineData = [];
            for (let i = 0; i < price_data.length; i++) {

                let index = lineData.push([price_data[i].dt, price_data[i].pr]) - 1; // 维度X 维度Y
                /*if (data[i].hasOwnProperty('info')) { // 满减凑单
                    console.log(index);
                    console.log(lineData[index]);
                    lineData[index].tooltip = {
                        formatter: '{c}啦啦啦，' + data[i].info.desc
                    };
                    // console.log(data[i]);
                }*/
            }

            /**
             * 处理标题
             * @type {string}
             */
            let title = '「' + result.title + '」价格走势'.toString();
            let fontSize = 16;
            let screenWidth = window.screen.width; // 屏幕分辨率宽
            let maxRowLen = Math.floor(screenWidth / fontSize); // 每行最多字数

            if (title.length > maxRowLen) { // 超过处替换为换行，以实现自动换行效果
                let regex = new RegExp('(?!^)(?=(.{' + maxRowLen + '}?)+$)', 'g'); // 匹配每最多个字数处
                title = title.replace(regex, '\n');
            }

            console.log(result);
            /**
             * 趋势
             * @type {string}
             */
            let trend = '';
            switch (result.price_data.trend) {
                case 1:
                    trend = '历史低价';
                    break;
                case 2:
                    trend = '价格下降';
                    break;
                case 3:
                    trend = '价格上涨';
                    break;
                case 4:
                default:
                    trend = '价格平稳'
            }
            console.log(trend);
            option.series[0].data = lineData;
            option.title = {
                text: title,
                subtext: '这是该商品从' + result.price_data.bd.replace(/\//g, '-') + '到' + result.price_data.ed.replace(/\//g, '-') + '的价格变动情况',
                x: 'center',
                top: 6,
                textStyle: {
                    // fontFamily: 'Microsoft YaHei',
                    fontSize: fontSize,
                },
            };

            // 仅移动端显示手柄
            if (OS.phone || OS.ipad) {
                option.xAxis.axisPointer.handle.show = true;
            }

            this.myChart.setOption(option); // 参数二，是否不跟之前设置的option进行合并，默认为false，即合并
        }
    };
}