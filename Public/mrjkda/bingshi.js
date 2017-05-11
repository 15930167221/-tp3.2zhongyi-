/**
 * Created by dark on 2016/12/19.
 */
$(document).ready(function(){
    //$("#duo").css("width",$("#duo").parent('span').parent('td').css("width"));
})
//家庭史
// 双击事件
$("#yw1").dblclick(function(){
    $("#jtsFarinp").val($("#yw1").val());
    $(".jtsFar").toggle();
})
// 点击提交事件
$("#jtsFartj").click(function(){
    $("#yw1").val($("#jtsFarinp").val());
    var len=$("#jtsFarinp").val().length;
    $("#yw1").css({"width":(len*15)+"px","min-width":"30px","max-width":$("#yw1").parent().parent().width(),"color":"red"});
    $(".jtsFar").hide();
})
// 选框选中事件
$(".jtscheck").click(function(){
    var jtsFarc="";
    var gmcheck=document.getElementsByClassName("jtscheck");
    for(var i=0;i<gmcheck.length;i++){
        if(gmcheck[i].checked){
            jtsFarc=gmcheck[i].value;
        }
    }
    $("#jtsFarinp").val(jtsFarc);
})
//过敏史
// 双击事件
$("#gms").dblclick(function(){
    $("#gmsinp").val($("#gms").val());
    $(".gm").toggle();
})
// 点击提交事件
$("#gmstj").click(function(){
    $("#gms").val($("#gmsinp").val());
    var len=$("#gmsinp").val().length;
    $("#gms").css({"width":(len*15)+"px","min-width":"30px","max-width":$("#gms").parent().parent().width(),"color":"red"});
    $(".gm").hide();
})
// 选框选中事件
$(".gmcheck").click(function(){
    var gmc="";
    var gmcheck=document.getElementsByClassName("gmcheck");
    for(var i=0;i<gmcheck.length;i++){
        if(gmcheck[i].checked){
            gmc+=gmcheck[i].value+",";
        }
    }
    $("#gmsinp").val(gmc.substr(0,gmc.length-1));
})
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
$(".hisInf").click(function(){
    if($(".hisContent").css('left')<'0px'){
        //俩弹框不能同时存在
        if($(".baseContent").css('left')>'0px'){
            $(".baseContent").animate({left:'-280px',opacity:'0'},1000);
            $(".baseContent").css('z-index','10');
            $(".hisContent").css('z-index','20');
            $(".hisContent").animate({left:'45px',opacity:'1'},1000);
        }else{
            $(".hisContent").animate({left:'45px',opacity:'1'},1000);
        }
    }else{
        $(".guanbi").parent().parent().animate({left:'-280px',opacity:'0'},1000);
    }
})
//关闭
$(".guanbi").click(function(){
    $(this).parent().parent().animate({left:'-280px',opacity:'0'},1000);
})
$(".health-file").click(function(){
    $(".guanbi").parent().parent().animate({left:'-280px',opacity:'0'},1000);
})
//滚动消失
$(".right-center").scroll(function(){
    $(".gm").hide();
    $(".jtsFar").hide();
    //$(".guanbi").parent().parent().animate({left:'-280px',opacity:'0'},1000);
})