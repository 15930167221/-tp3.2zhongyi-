/**
 * 辨证已选未选全选
 * 亚
 * 170315
 */
//-----------------------------------------------------主症-------------------------------------
//检索
// 已选
$(document).on("click","#zhuzjsyixuan",function(){
	// alert(123);
    // 把选择的显示为选择的隐藏
    $("#zhuzxjsfuzhi :radio").each(function () { 
    	$(this).parents("tr").show(); 
    	if(!$(this).prop("checked")){
    		$(this).parents("tr").hide();
    	}
    });
});
// 未选
$(document).on("click","#zhuzjsweixuan",function(){
	$("#zhuzxjsfuzhi :radio").each(function () { 
		$(this).parents("tr").show(); 
    	if($(this).prop("checked")){
    		$(this).parents("tr").hide();
    	}
    });
});
// 把所有的显示
$(document).on("click","#zhuzjsxsqb",function(){
	$("#zhuzxjsfuzhi :checked").each(function () {  
       $(this).parents("tr").show();  
    });
});
//常用选择
// 已选
$(document).on("click","#zhuzfenleiyix",function(){
    // alert(123);
    // 把选择的显示为选择的隐藏
    $("#chyongxuzjigfuzhi :radio").each(function () { 
        $(this).parents("tr").show(); 
        if(!$(this).prop("checked")){
            $(this).parents("tr").hide();
        }
    });
});
// 未选
$(document).on("click","#zhuzfenleiweixuan",function(){
    $("#chyongxuzjigfuzhi :radio").each(function () { 
        $(this).parents("tr").show(); 
        if($(this).prop("checked")){
            $(this).parents("tr").hide();
        }
    });
});
// 把所有的显示
$(document).on("click","#zhuzfenleixsqubu",function(){
    $("#chyongxuzjigfuzhi :checked").each(function () {  
       $(this).parents("tr").show();  
    });
});
//-----------------------------------------------------兼症-------------------------------------
//分类
// 已选
$(document).on("click","#jianzfenleiyixuan",function(){
    // alert(123);
    // 把选择的显示为选择的隐藏
    $("#jzflfuzhi :checkbox").each(function () { 
        $(this).parents("tr").show(); 
        if(!$(this).prop("checked")){
            $(this).parents("tr").hide();
        }
    });
});
// 未选
$(document).on("click","#jianzfenleiweixuan",function(){
    $("#jzflfuzhi :checkbox").each(function () { 
        $(this).parents("tr").show(); 
        if($(this).prop("checked")){
            $(this).parents("tr").hide();
        }
    });
});
// 把所有的显示
$(document).on("click","#jianzfenleixsqb",function(){
    $("#jzflfuzhi :checked").each(function () {  
       $(this).parents("tr").show();  
    });
});
//检索
// 已选
$(document).on("click","#jianzjiansyixuan",function(){
    // alert(123);
    // 把选择的显示为选择的隐藏
    $(".jzjssousuozhifuzhi :checkbox").each(function () { 
        $(this).parents("tr").show(); 
        if(!$(this).prop("checked")){
            $(this).parents("tr").hide();
        }
    });
});
// 未选
$(document).on("click","#jianzjiansweixuan",function(){
    $(".jzjssousuozhifuzhi :checkbox").each(function () { 
        $(this).parents("tr").show(); 
        if($(this).prop("checked")){
            $(this).parents("tr").hide();
        }
    });
});
// 把所有的显示
$(document).on("click","#jianzjiansxiansqb",function(){
    $(".jzjssousuozhifuzhi :checked").each(function () {  
       $(this).parents("tr").show();  
    });
});
//常用选择
// 已选
$(document).on("click","#jianzcyxzyixuan",function(){
    // alert(123);
    // 把选择的显示为选择的隐藏
    $("#jzjzcyxxfzzhixing :checkbox").each(function () { 
        $(this).parents("tr").show(); 
        if(!$(this).prop("checked")){
            $(this).parents("tr").hide();
        }
    });
});
// 未选
$(document).on("click","#jianzcyxzweixuan",function(){
    $("#jzjzcyxxfzzhixing :checkbox").each(function () { 
        $(this).parents("tr").show(); 
        if($(this).prop("checked")){
            $(this).parents("tr").hide();
        }
    });
});
// 把所有的显示
$(document).on("click","#jianzcyxzxsqb",function(){
    $("#jzjzcyxxfzzhixing :checked").each(function () {  
       $(this).parents("tr").show();  
    });
});
//-----------------------------------------------------舌象-------------------------------------
//分类
// 已选
$(document).on("click","#shexiangfenlyxuan",function(){
    // alert(123);
    // 把选择的显示为选择的隐藏
    $("#shexiangflfuzhi :checkbox").each(function () { 
        $(this).parents("tr").show(); 
        if(!$(this).prop("checked")){
            $(this).parents("tr").hide();
        }
    });
});
// 未选
$(document).on("click","#shexiangfenlweixuan",function(){
    $("#shexiangflfuzhi :checkbox").each(function () { 
        $(this).parents("tr").show(); 
        if($(this).prop("checked")){
            $(this).parents("tr").hide();
        }
    });
});
// 把所有的显示
$(document).on("click","#shexiangfenlxsqb",function(){
    // alert(123);
    $("#shexiangflfuzhi :checked").each(function () {  
       $(this).parents("tr").show();  
    });
});
//检索
// 已选
$(document).on("click","#shexiangjiangsyixuan",function(){
    // alert(123);
    // 把选择的显示为选择的隐藏
    $("#shexiangjssousuofuzhi :checkbox").each(function () { 
        $(this).parents("tr").show(); 
        if(!$(this).prop("checked")){
            $(this).parents("tr").hide();
        }
    });
});
// 未选
$(document).on("click","#shexiangjiangsweixuan",function(){
    $("#shexiangjssousuofuzhi :checkbox").each(function () { 
        $(this).parents("tr").show(); 
        if($(this).prop("checked")){
            $(this).parents("tr").hide();
        }
    });
});
// 把所有的显示
$(document).on("click","#shexiangjiangsxsqb",function(){
    // alert(123);
    $("#shexiangjssousuofuzhi :checked").each(function () {  
       $(this).parents("tr").show();  
    });
});
//常用选择
// 已选
$(document).on("click","#jianzhengcyxzyixuan",function(){
    // alert(123);
    // 把选择的显示为选择的隐藏
    $("#shexiangcyxzsousuokuangguzhi :checkbox").each(function () { 
        $(this).parents("tr").show(); 
        if(!$(this).prop("checked")){
            $(this).parents("tr").hide();
        }
    });
});
// 未选
$(document).on("click","#jianzhengcyxzweixuan",function(){
    $("#shexiangcyxzsousuokuangguzhi :checkbox").each(function () { 
        $(this).parents("tr").show(); 
        if($(this).prop("checked")){
            $(this).parents("tr").hide();
        }
    });
});
// 把所有的显示
$(document).on("click","#jianzhengcyxzxsqb",function(){
    // alert(123);
    $("#shexiangcyxzsousuokuangguzhi :checked").each(function () {  
       $(this).parents("tr").show();  
    });
});
//-----------------------------------------------------脉相-------------------------------------
//分类
// 已选
$(document).on("click","#maixiangfenlyixuan",function(){
    // alert(123);
    // 把选择的显示为选择的隐藏
    $("#maixiangflfuzhi :checkbox").each(function () { 
        $(this).parents("tr").show(); 
        if(!$(this).prop("checked")){
            $(this).parents("tr").hide();
        }
    });
});
// 未选
$(document).on("click","#maixiangfenlweixuan",function(){
    $("#maixiangflfuzhi :checkbox").each(function () { 
        $(this).parents("tr").show(); 
        if($(this).prop("checked")){
            $(this).parents("tr").hide();
        }
    });
});
// 把所有的显示
$(document).on("click","#maixiangfenlxsqb",function(){
    // alert(123);
    $("#maixiangflfuzhi :checked").each(function () {  
       $(this).parents("tr").show();  
    });
});

//检索
// 已选
$(document).on("click","#maixiangjiansuoyixuan",function(){
    // alert(123);
    // 把选择的显示为选择的隐藏
    $("#maixiangjssousuofuzhi :checkbox").each(function () { 
        $(this).parents("tr").show(); 
        if(!$(this).prop("checked")){
            $(this).parents("tr").hide();
        }
    });
});
// 未选
$(document).on("click","#maixiangjiansuoweixuan",function(){
    $("#maixiangjssousuofuzhi :checkbox").each(function () { 
        $(this).parents("tr").show(); 
        if($(this).prop("checked")){
            $(this).parents("tr").hide();
        }
    });
});
// 把所有的显示
$(document).on("click","#maixiangjiansuoxsqb",function(){
    // alert(123);
    $("#maixiangjssousuofuzhi :checked").each(function () {  
       $(this).parents("tr").show();  
    });
});
//常用选择
// 已选
$(document).on("click","#maixiangcyxzyixuan",function(){
    // alert(123);
    // 把选择的显示为选择的隐藏
    $("#maixiangcyxzsousuokuangguzhi :checkbox").each(function () { 
        $(this).parents("tr").show(); 
        if(!$(this).prop("checked")){
            $(this).parents("tr").hide();
        }
    });
});
// 未选
$(document).on("click","#maixiangcyxzweixuan",function(){
    $("#maixiangcyxzsousuokuangguzhi :checkbox").each(function () { 
        $(this).parents("tr").show(); 
        if($(this).prop("checked")){
            $(this).parents("tr").hide();
        }
    });
});
// 把所有的显示
$(document).on("click","#maixiangcyxzxsqb",function(){
    // alert(123);
    $("#maixiangcyxzsousuokuangguzhi :checked").each(function () {  
       $(this).parents("tr").show();  
    });
});
