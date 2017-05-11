/**
 * Created by dark on 2017/1/18.
 */
$(document).on("click",".sty1",function(){
    var tsty1=document.getElementsByName("tableSty");
    for(var i=0;i<tsty1.length;i++){
        tsty1[i].className="sty1";
    }
    $(this).attr("class","sty2");
    // alert($(".current").html());
    //给home元素赋病人信息
    $("#sblh", parent.document).html($(this).find(".ajaxcanshu").val());
    // 赋值序号
    $("#sblhxh", parent.document).val($(this).find(".ajaxcanshuxh").val());
    // 赋值那天
    $("#hznatian", parent.document).html($('#xdri').val());
    $("#sxm", parent.document).html($(this).find(".brname").val());
    $("#sbxb", parent.document).html($(this).find(".bxbvo").val());
    $("#sbnl", parent.document).html($(this).find(".brnlvo").val());
    $("#sbdate", parent.document).html($(this).find(".datefenmiao").html());
    $("#sbnl2", parent.document).html($(this).find(".nlt").val());//库中年龄
    $("#jkSave",parent.document).html(0);    //清空jkSave标记
});
$(document).on("click",".cxtr1",function(){
    var cxtsty1=document.getElementsByName("cxtableSty");
    for(var i=0;i<cxtsty1.length;i++){
        cxtsty1[i].className="cxtr1";
    }
    $(this).attr("class","cxtr2");
});
