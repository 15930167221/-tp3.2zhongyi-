/**
 * Created by Administrator on 2017/3/4.
 */
function jisuan()
{
  
    wen = 0;
    re = 0;
    ping = 0;
    han = 0;
    weiwen = 0;
    weihan = 0;
    liang = 0;
    dahan = 0;
    dare = 0;
    $('.cfmx').find('.wrphhid').each(function(){
        aa = $(this).val();

        if(aa == '温'){
            wen = wen*1+1;
        }else if(aa == '热'){
            re = re*1+1;
        }else if(aa == '平'){
            ping = ping*1+1;
        }else if(aa == '寒'){
            han = han*1+1;
        }else if(aa == '微温'){
            weiwen = weiwen*1+1;
        }else if(aa == '微寒'){
            weihan = weihan*1+1;
        }else if(aa == '凉'){
            liang = liang*1+1;
        }else if(aa == '大寒'){
            dahan = dahan*1+1;
        }else if(aa == '大热'){
            dare = dare*1+1;
        }
    });
    zong = $('.cfmx').find('.wrphhid').length;
    wenbi = Math.round((wen / zong * 10000) / 100.00);
    rebi = Math.round((re / zong * 10000) / 100.00);
    pingbi = Math.round((ping / zong * 10000) / 100.00);
    hanbi = Math.round((han / zong * 10000) / 100.00);
    weiwenbi = Math.round((weiwen / zong * 10000) / 100.00);
    weihanbi = Math.round((weihan / zong * 10000) / 100.00);
    liangbi = Math.round((liang / zong * 10000) / 100.00);
    dahanbi = Math.round((dahan / zong * 10000) / 100.00);
    darebi = Math.round((dare / zong * 10000) / 100.00);
    jieguo = [wenbi,rebi,pingbi,hanbi,weiwenbi,weihanbi,dahanbi,darebi,liangbi];
    return jieguo;
}
function resetSh(id){
    $.ajax({
                url:"{:U('Ajax/resSh')}",
                type:"post",
                data:{code:id},
                dataType:"json",
                success:function(dd){
                  alert(123);
                },
                error:function(){
                    alert('Ajax请求失败');
                }
            })
}
function jisuan2()
{
   wen = 0;
    re = 0;
    ping = 0;
    han = 0;
    weiwen = 0;
    weihan = 0;
    liang = 0;
    dare = 0;
    dahan = 0;
    $('.wrphhid').each(function(){
        aa = $(this).val();

        if(aa == '温'){
            wen = wen*1+1;
        }else if(aa == '热'){
            re = re*1+1;
        }else if(aa == '平'){
            ping = ping*1+1;
        }else if(aa == '寒'){
            han = han*1+1;
        }else if(aa == '微温'){
            weiwen = weiwen*1+1;
        }else if(aa == '微寒'){
            weihan = weihan*1+1;
        }else if(aa == '凉'){
            liang = liang*1+1;
        }else if(aa == '大热'){
            dare = dare*1+1;
        }else if(aa = '大寒'){
            dahan = dahan*1+1;
        }
    });
    zong = $('.wrphhid').length;
   wenbi = Math.round((wen / zong * 10000) / 100.00);
    rebi = Math.round((re / zong * 10000) / 100.00);
    pingbi = Math.round((ping / zong * 10000) / 100.00);
    hanbi = Math.round((han / zong * 10000) / 100.00);
    weiwenbi = Math.round((weiwen / zong * 10000) / 100.00);
    weihanbi = Math.round((weihan / zong * 10000) / 100.00);
    liangbi = Math.round((liang / zong * 10000) / 100.00);
    dahanbi = Math.round((dahan / zong * 10000) / 100.00);
    darebi = Math.round((dare / zong * 10000) / 100.00);
    jieguo = [wenbi,rebi,pingbi,hanbi,weiwenbi,weihanbi,dahanbi,darebi,liangbi];
    return jieguo;
}
function  setOp()
{
    myChart.setOption({
        series:[{
            data:jisuan()
        }]
    })
}

function  setOp2()
{
    myChart.setOption({
        series:[{
            data:jisuan2()
        }]
    })
}
function yuanCf(zywrph){
    wen1 = 0;
    re1 = 0;
    ping1 = 0;
    han1 = 0;
    weiwen1 = 0;
    weihan1 = 0;
    liang1 = 0;
    dahan1 = 0;
    dare1 = 0;
    $.each(zywrph,function(k,v){
        var aa = v;

        if(aa == '温'){
            wen1 = wen1*1+1;
        }else if(aa == '热'){
            re1 = re1*1+1;
        }else if(aa == '平'){
            ping1 = ping1*1+1;
        }else if(aa == '寒'){
            han1 = han1*1+1;
        }else if(aa == '微温'){
            weiwen1 = weiwen1*1+1;
        }else if(aa == '微寒'){
            weihan1 = weihan1*1+1;
        }else if(aa == '凉'){
            liang1 = liang1*1+1;
        }else if(aa == '大寒'){
            dahan1 = dahan1*1+1;
        }else if(aa == '大热'){
            dare1 = dare1*1+1;
        }
    });
    jieguo1 = [
                ['温',   wen1],
                ['热',       re1],
                {
                    name: '平',
                    y: ping1,
                    sliced: true,
                    selected: true
                },
                ['寒',    han1],
                ['微温',    weiwen1],
                ['微寒',   weihan1],
                ['凉',  liang1]
            ];
    return jieguy1;
}

function setHop(zywrph)
{
     wen1 = 0;
    re1 = 0;
    ping1 = 0;
    han1 = 0;
    weiwen1 = 0;
    weihan1 = 0;
    liang1 = 0;
    dahan1 = 0;
    dare1 = 0;
    $.each(zywrph,function(k,v){
        var aa = v;

        if(aa == '温'){
            wen1 = wen1*1+1;
        }else if(aa == '热'){
            re1 = re1*1+1;
        }else if(aa == '平'){
            ping1 = ping1*1+1;
        }else if(aa == '寒'){
            han1 = han1*1+1;
        }else if(aa == '微温'){
            weiwen1 = weiwen1*1+1;
        }else if(aa == '微寒'){
            weihan1 = weihan1*1+1;
        }else if(aa == '凉'){
            liang1 = liang1*1+1;
        }else if(aa == '大寒'){
            dahan1 = dahan1*1+1;
        }else if(aa == '大热'){
            dare1 = dare1*1+1;
        }
    });
  
    $('#bing11').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            backgroundColor: 'rgba(0,0,0,0)'
        },
        colors:[
                        '#FAD348',//第一个颜色，
                        '#F28504',//第二个颜色
                        '#4FCD71',//第三个颜色
                       '#0DB4E9', //。。。。
                           '#EBA14A',
                           '#8AC9DC', 
                           '#63C3BF', 
                           '#F25C18', 
                           '#0D78E9'
                      ],
        title: {
            text: null
        },
        legend: {
            enabled: false
        },
        exporting:{
                    enabled:false
                },
                credits: {
                    enabled: false
                },
        tooltip: {
            pointFormat: '<b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                        formatter: function() {  
                   if (this.percentage > 0)  
                       return '<b style="font-size:13px;font-weight:normal;">' + this.point.name + '</b>: <b style="font-size:13px;font-weight:normal;">' + Math.round(this.point.percentage) + ' %</b>'; //这里进行判断（看这里）  
               },  
                },
                showInLegend: true,
            }
        },
        series: [{
            type: 'pie',
            name: 'Browser share',
            data: [
                ['温',   wen1],
                ['热',       re1],
                {
                    name: '平',
                    y: ping1,
                    sliced: true,
                    selected: true
                },
                ['寒',    han1],
                ['微温',    weiwen1],
                ['微寒',   weihan1],
                ['凉',  liang1],
                ['大热',  dare1],
                ['大寒',  dahan1]
            ]
        }]
    })
}
function setHop2(zywrph)
{
    wen11 = 0;
    re11 = 0;
    ping11 = 0;
    han11 = 0;
    weiwen11 = 0;
    weihan11 = 0;
    liang11 = 0;
    dahan11 = 0;
    dare11 = 0;
    $.each(zywrph,function(k,v){
        var aa = v;

        if(aa == '温'){
            wen11 = wen11*1+1;
        }else if(aa == '热'){
            re11 = re11*1+1;
        }else if(aa == '平'){
            ping11 = ping11*1+1;
        }else if(aa == '寒'){
            han11 = han11*1+1;
        }else if(aa == '微温'){
            weiwen11 = weiwen11*1+1;
        }else if(aa == '微寒'){
            weihan11 = weihan11*1+1;
        }else if(aa == '凉'){
            liang11 = liang11*1+1;
        }else if(aa == '大寒'){
            dahan11 = dahan11*1+1;
        }else if(aa == '大热'){
            dare11 = dare11*1+1;
        }
    });
  
    $('#bing22').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            backgroundColor: 'rgba(0,0,0,0)'
        },
         colors:[
                        '#FAD348',//第一个颜色
                        '#F28504',//第二个颜色
                        '#4FCD71',//第三个颜色
                       '#0DB4E9', //。。。。
                           '#EBA14A',
                           '#8AC9DC', 
                           '#63C3BF', 
                           '#F25C18', 
                           '#0D78E9'
                      ],
               title: {
            text: null
        },
        legend: {
            enabled: false
        },
        exporting:{
                    enabled:false
                },
                credits: {
                    enabled: false
                },
        tooltip: {
            pointFormat: '<b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                        formatter: function() {  
                   if (this.percentage > 0)  
                       return '<b style="font-size:13px;font-weight:normal;">' + this.point.name + '</b>: <b style="font-size:13px;font-weight:normal;">' + Math.round(this.point.percentage) + ' %</b>'; //这里进行判断（看这里）  
               },  
                },
                showInLegend: true,
            }
        },
        series: [{
            type: 'pie',
            name: 'Browser share',
            data: [
                ['温',   wen11],
                ['热',       re11],
                {
                    name: '平',
                    y: ping11,
                    sliced: true,
                    selected: true
                },
                ['寒',    han11],
                ['微温',    weiwen11],
                ['微寒',   weihan11],
                ['凉',  liang11],
                ['大热',  dare11],
                ['大寒',  dahan11]
            ]
        }]
    })
}
