/**
 * Created by dark on 2017/1/18.
 */
//详细信息
$(document).on("click",".ajaxxinxishuju",function(){
    $id = $(this).parent().parent().find(".ajaxcanshu").val();
    var xh = $(this).parent().parent().find(".brxh").html();
    var urlxingxixingxi = $("#xiangxixinxiurlzhi").val();
    $.ajax({
        type:'get',
        url:urlxingxixingxi,
        data:{"id":$id,"xh":xh},
        dataType:'json',
        success:function(dd)
        {
            // 获取对应的病人基本信息
            $br_id = dd[0]['br_id'];
            $br_name = dd[0]['br_name'];
            $nl = dd[0]['nl'];
            $cs_date = dd[0]['cs_date'];
            $pass = dd[0]['pass'];
            $dw = dd[0]['dw'];
            $tel = dd[0]['tel'];
            $xb = dd[0]['xb'];
            $email = dd[0]['e_mail'];
            $ghf = dd[0]['ghf'];
            $fax = dd[0]['fax'];
            $p_date = dd[0]['p_date'];
            // 通过js将信息添加到页面
            $("#ajaxmtbrid").val($br_id);
            $("#ajaxmtbrname").val($br_name);
            $("#ajaxmtnl").val($nl);
            $("#ajaxmtcsdate").val($cs_date);
            $("#ajaxmtpass").val($pass);
            $("#ajaxmtdw").val($dw);
            $("#ajaxmttel").val($tel);
            // 判断性别
            if($xb=="男"){
                $("#ajaxmtxbnan").attr("checked","checked");
            }else if($xb=="女"){
                $("#ajaxmtxbnv").attr("checked","checked");
            }
            $("#ajaxmtemail").val($email);
            $("#ajaxmtghf").val($ghf);
            $("#ajaxmtfax").val($fax);
            $("#ajaxmtpdate").val($p_date);
        }
    });
});
//点击改变上侧值
$(document).on("click",".sty1",function(){
    var tsty1=document.getElementsByName("tableSty");
    for(var i=0;i<tsty1.length;i++){
        tsty1[i].className="sty1";
    }
    $(this).attr("class","sty2");
    // alert($(this).find("#jz_date").html());
    //给home元素赋病人信息
    $("#sblh", parent.document).html($(this).find(".ajaxcanshu").val());
    // 赋值序号
    $("#sblhxh", parent.document).val($(this).find(".ajaxcanshuxh").val());
    // 赋值那天
    $("#hznatian", parent.document).html($('#xdri').val());
    $("#sxm", parent.document).html($(this).find(".brname").val());
    $("#sbxb", parent.document).html($(this).find(".bxbvo").val());
    $("#sbnl", parent.document).html($(this).find(".brnlvo").val());
    $("#sbdate", parent.document).html($(this).find("#jz_date").html());
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
