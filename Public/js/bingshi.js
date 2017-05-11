/**
 * Created by dark on 2016/12/19.
 */
//病人基本信息弹出
$(".baseInf").click(function(){
    if($(".baseContent").css('left')<'0px'){
        //俩弹框不能同时存在
        if($(".hisContent").css('left')>'0px'){
            $(".hisContent").animate({left:'-280px',opacity:'0'},1000);
            $(".hisContent").css('z-index','10');
            $(".baseContent").css('z-index','20');
            $(".baseContent").animate({left:'45px',opacity:'1'},1000);
        }else{
            $(".baseContent").animate({left:'45px',opacity:'1'},1000);
        }
    }else{
        $(".guanbi").parent().parent().animate({left:'-280px',opacity:'0'},1000);
    }
})
//历史就诊记录弹出
$(".hisInf1").click(function(){
    if($(".hisContent").css('right')<'0px'){
        //俩弹框不能同时存在
        if($(".baseContent").css('right')>'0px'){
            $(".baseContent").animate({right:'-300px',opacity:'0'},1000);
            $(".baseContent").css('z-index','10');
            $(".hisContent").css('z-index','20');
            $(".hisContent").animate({right:'0px',opacity:'1'},1000);
        }else{
            $(".hisContent").animate({right:'0px',opacity:'1'},1000);
        }
    }else{
        $(".guanbi").parent().parent().animate({right:'-300px',opacity:'0'},1000);
    }
})
//关闭
$(".guanbi").click(function(){
    $(this).parent().parent().animate({right:'-280px',opacity:'0'},1000);
})

//该功能和双击弹出框冲突
//$(".health-file").click(function(){
//    //alert(11);
//    $(".guanbi").parent().parent().animate({left:'-280px',opacity:'0'},1000);
//})

//家庭史
/**
 * 双击弹出事件
 * @param inp 要双击的input框的id
 * @param kuang  双击后弹出的div的class
 * @param kuangInp 弹出框中的input框的id
 */
function douCli(inp,kuang,kuangInp){
    $(".clo").not($("#"+kuangInp).nextAll('clo')).click();//只能打开一个框
    $("."+kuang).children('.div1').css('display','block');
    var offset=$("#"+inp).offset();
    $("."+kuang).css("left","0px");
    $("."+kuang).css("top","0px");
    $("."+kuang).offset({top:offset.top+20,left:offset.left});
    $("#"+kuangInp).val($("#"+inp).val());
    $("."+kuang).toggle();
}
/**
 * 点击“提交”按钮进行提交
 * @param inp 提交后显示提交内容的input框的id
 * @param kuangInp  弹出框中的input框的id
 * @param kuang  双击后弹出的div的class
 */
function sub(inp,kuang,kuangInp){
    $("#"+inp).attr("value",$("#"+kuangInp).val());
    $("#"+inp).val($("#"+kuangInp).val());//打印功能失效
    var len=$("#"+kuangInp).val().length;
    $("#"+inp).css({"width":(len*15)+"px","min-width":"30px","max-width":$("#"+inp).parent().parent().width(),"color":"red"});
    $("."+kuang).hide();
}
/**
 * 单选框选中input内容改变事件
 * @param clas  单选框的类名
 * @param kuangInp 弹出框中的input框的id
 */
function checked1(clas,kuangInp){
    var jtsFarc="";
    var gmcheck=document.getElementsByClassName(clas);
    for(var i=0;i<gmcheck.length;i++){
        if(gmcheck[i].checked){
            jtsFarc=gmcheck[i].value;
        }
    }
    $("#"+kuangInp).val(jtsFarc);
}
/**
 * 单选按钮双击内容提交
 */
$(document).on('dblclick','.dblCli',function(){
    $("#"+$(this).children().attr("class")).val($(this).children().val());
    $("#"+$(this).children().attr("class")).attr("value",$(this).children().val());
    var len=$("#"+$(this).parent().prev().children("input[type='text']").attr('id')).val().length;
    $("#"+$(this).children().attr("class")).css({"width":(len*15)+"px","min-width":"30px","max-width":$("#"+$(this).children().attr("class")).parent().parent().width(),"color":"red"});
    $(this).parent().parent().hide();
})
/**
 * 多选框选中input内容改变事件
 * @param clas  单选框的类名
 * @param kuangInp 弹出框中的input框的id
 */
function checked2(clas,kuangInp){
    var jtsFarc="";
    var gmcheck=document.getElementsByClassName(clas);
    for(var i=0;i<gmcheck.length;i++){
        if(gmcheck[i].checked){
            jtsFarc+=gmcheck[i].value+",";
        }
    }
    $("#"+kuangInp).val(jtsFarc.substr(0,jtsFarc.length-1));
}
//滚动消失
$(".right-center").scroll(function(){
    $(".gm").hide();
    $(".jtsFar").hide();
    $(".jtsMother").hide();
    $(".jts3").hide();
    $(".jts4").hide();
    $(".jts11,.jts22,.jts33,.jts44").hide();
    $(".zt1,.zt2,.zt3,.zt4").hide();
    $(".xz1,.xz2,.xz3,.xz4,.xz5,.xz6,.xz7,.xz8").hide();
    $(".qz1,.qz2,.xj1").hide();
    $(".sz1,.sz2,.sz3,.sz4,.sz5,.mz1").hide();
    $(".jws1,.jws2").hide();
    $(".zyzd1,.xyzd1").hide();
    //$(".guanbi").parent().parent().animate({left:'-280px',opacity:'0'},1000);
})
//点击关闭按钮进行关闭
$(".clo").click(function(){
    $(this).parent('.div1').parent().hide();
})
$(window).ready(function(){
    if($("#name").val()!=''){
        $("#name").attr("size",$("#name").val().length*1.2);
    }
    if($("#jws11").val()!=''){
        $("#jws11").attr("size",$("#jws11").val().length*1.8);
    }if($("#crbs").val()!=''){
        $("#crbs").attr("size",$("#crbs").val().length*1.8);
    }if($("#jtsFQ").val()!=''){
        $("#jtsFQ").attr("size",$("#jtsFQ").val().length*1.8);
    }if($("#jtsMQ").val()!=''){
        $("#jtsMQ").attr("size",$("#jtsMQ").val().length*1.8);
    }if($("#jtsXD").val()!=''){
        $("#jtsXD").attr("size",$("#jtsXD").val().length*1.8);
    }if($("#jtsZN").val()!=''){
        $("#jtsZN").attr("size",$("#jtsZN").val().length*1.8);
    }if($("#gms").val()!=''){
        $("#gms").attr("size",$("#gms").val().length*1.8);
    }if($("#zl").val()!=''){
        $("#zl").attr("size",$("#zl").val().length*1.5);
    }if($("#mz").val()!=''){
        $("#mz").attr("size",$("#mz").val().length*1.2);
    }if($("#zyzd").val()!=''){
        $("#zyzd").attr("size",$("#zyzd").val().length*1.5);
    }if($("#xyzd").val()!=''){
        $("#xyzd").attr("size",$("#xyzd").val().length*1.5);
    }if($("#zybz").val()!=''){
        $("#zybz").attr("size",$("#zybz").val().length*1.5);
    }if($("#zyzz").val()!=''){
        $("#zyzz").attr("size",$("#zyzz").val().length*1.5);
    }
    //中药处置
    //中药名
    $(".zychinf1").each(function(){
        $(this).attr('size',$(this).val().length*2);
    })
    $(document).on("input",".zychinf1",function(){
        $(".zychinf1").each(function(){
            $(this).attr("value",$(this).val());
        })
    })
    //克数
    $(".zychinf2").each(function(){
        $(this).attr('size',$(this).val().length*1);
    })
    $(document).on("input",".zychinf2",function(){
        $(".zychinf2").each(function(){
            $(this).attr("value",$(this).val());
        })
    })
    //用法
    $(".zychinf4").each(function(){
        $(this).attr('size',$(this).val().length*1.5);
    })
    $(document).on("input",".zychinf4",function(){
        $(".zychinf4").each(function(){
            $(this).attr("value",$(this).val());
        })
    })
    //西药处置
    $(document).on("input",".xychinf",function(){
        $(".xychinf").each(function(){
            $(this).attr("value",$(this).val());
        })
    })
});
//点击变色
$(document).on("click",".sty1",function(){
    var tsty1=document.getElementsByName("tableSty");
    for(var i=0;i<tsty1.length;i++){
        tsty1[i].className="sty1";
    }
    $(this).attr("class","sty2");
});