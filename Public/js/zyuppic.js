function suan()
{
  
    var wena = 0;
    var rea = 0;
    var pinga = 0;
    var hana = 0;
    var weiwena = 0;
    var weihana = 0;
    var lianga = 0;
    var dahana = 0;
    var darea = 0;
    $('.wrphhid').each(function(){
        aa = $(this).val();
        if(aa == '温'){
            wena = wena*1+1;
        }else if(aa == '热'){
            rea = rea*1+1;
        }else if(aa == '平'){
            pinga = pinga*1+1;
        }else if(aa == '寒'){
            hana = hana*1+1;
        }else if(aa == '微温'){
            weiwena = weiwena*1+1;
        }else if(aa == '微寒'){
            weihana = weihana*1+1;
        }else if(aa == '凉'){
            lianga = lianga*1+1;
        }else if(aa == '大寒'){
            dahana = dahana*1+1;
        }else if(aa == '大热'){
            darea = darea*1+1;
        }
    });
    zonga = $('.cfmx').find('.wrphhid').length;
    wenbia = Math.round((wena / zonga * 10000) / 100.00);
    rebia = Math.round((rea / zonga * 10000) / 100.00);
    pingbia = Math.round((pinga / zonga * 10000) / 100.00);
    hanbia = Math.round((hana / zonga * 10000) / 100.00);
    weiwenbia = Math.round((weiwena / zonga * 10000) / 100.00);
    weihanbia = Math.round((weihana / zonga * 10000) / 100.00);
    liangbia = Math.round((lianga / zonga * 10000) / 100.00);
    dahanbia = Math.round((dahana / zonga * 10000) / 100.00);
    darebia = Math.round((darea / zonga * 10000) / 100.00);
    jieguoa = [wenbia,rebia,pingbia,hanbia,weiwenbia,weihanbia,darebia,dahanbia,liangbia];
    return jieguoa;
}
function give(data){
   $('#wrphpic').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: null
        },
        subtitle: {
            text: null
        },
        xAxis: {
            categories: ['温', '热', '平', '寒', '微温','微寒','大热','大寒','凉'],
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: null,
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },

        tooltip: {
            valueSuffix: ' %'
        },
        plotOptions: {
             series: {

   cursor: 'pointer',
   events: {
    click: function(e) {
        $('.zykf_yp').css('backgroundColor','#FBFBFB');
        $('.wrphhid').each(function(){
            if($(this).val()==e.point.category){
                $(this).parent().parent().css('backgroundColor','rgba(124,181,236,0.3)');
                // $('.wrphhid').not($(this)).parent().parent().css('backgroundColor','#FFF');
            }
        });
     // alert(e.point.category);
   
    }
   }},
            bar: {
                dataLabels: {
                    enabled: true,
                    allowOverlap: true
                }
            }
        },
        legend: {
            //隐藏掉series name
            enabled:false
        },
        credits: {
            enabled: false
        },
        series: [{
            name: '占比',
            data: data
        }]
    });

}