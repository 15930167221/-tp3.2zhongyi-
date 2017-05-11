function Days(){
	//计算处方天数
	var shul = $('.tr2:last').find('td').eq(6).find('input').val();
	var baozl = $('.tr2:last').find('td').eq(5).find('input').val();
	var hanl = $('.tr2:last').find('td').eq(4).find('input').val();
	var ciyl = $('.tr2:last').find('td').eq(9).find('input').val();
	var pinl = $('.tr2:last').find('td').eq(10).find('select').val();
	var day = shul*baozl*hanl/ciyl/pinl;
	// alert(baozl);
	$('.tr2:last').find('td:last').find('input').val(day);
	// alert(shul);
}
$(function(){
	var str =  $('.tr2:last .textlon:nth(1)').html();
		var plstr = $('.tr2:last .textlon:nth(2)').html();
	
	//减药
	$(document).on('mouseover','.tr2 .td1:first-child',function(){
		$(this).css('backgroundColor','#D9532F');
		$(this).click(function(){
			$(this).parent().detach();
		});
	}).on('mouseout','.tr2 .td1:first-child',function(){
		$(this).css('backgroundColor','#E57976');
	});

	// 加一味药
	$('#jia').click(function(){
	 if($('.tr2').length>=5){
	 	layer.alert('最多五种药品哦',{icon:7});
	 }else{
	 	if($('.tr2').length<1){
		$('.tr1').after('<tr class="tr2"><td class="td1">X</td><td class="td1"><input type="text" name="bianma" class="textlon"></td><td class="td1"><input type="text"  class="ypname" name="mingcheng"></td><td class="td1"><input type="text" class="sspan" readonly="readonly" name="guige"></td><td class="td1"><input type="text" class="sspan" readonly="readonly" name="hanliang"></td><td class="td1"><input type="text" class="sspan" readonly="readonly" name="baozhuang"></td><td class="td1"><input type="text" class="textlonsl ypnum" value="1" name="shuliang"><span class="sldw"></span></td><td class="td1"><input type="text" class="sspan" readonly="readonly" name="zongliang"></td><td class="td1"><select class="textlon tuj" name="tujing">'+str+'</select></td><td class="td1"><input type="text" class="textlonsl ypcyl" value="1" name="yongliang"><span class="ylxdw"></span></td><td class="td1"><select class="textlon pinl" name="cishu">'+plstr+'</select></td><td class="td1"><select name="tsyf" class="textlon yongf"><option value="0">无</option><option value="1">皮试</option><option value="2">小壶</option></select></td><td class="td1"><input type="text"  class="textlon ypdays sspan" readonly="readonly" name="tianshu"></td></tr>');
	 }else{
	 	var nstr =  $('.tr2:last .textlon:nth(1)').html();
		var nplstr = $('.tr2:last .textlon:nth(2)').html();
		if($('.tr2:last .textlon:nth(0)').val().length < 2){
			
		}else if($('.tr2:last .textlon:nth(1)').val().length < 1){
			layer.alert('请输入药品数量',{icon:7});
		}else if($('.ypname:last').val().length<1){
			layer.alert('请输入药品名',{icon:7})
		}else if($('.tr2:last .textlon:nth(3)').val().length < 1){
			layer.alert('请输入次用量',{icon:7});
		}else{
			var id = $('.b1:last').html();
		
		$('.tr2:last').after('<tr class="tr2"><td class="td1">X</td><td class="td1"><input type="text" name="bianma" class="textlon"></td><td class="td1"><input type="text"  class="ypname" name="mingcheng"></td><td class="td1"><input type="text" class="sspan" readonly="readonly" name="guige"></td><td class="td1"><input type="text" class="sspan" readonly="readonly" name="hanliang"></td><td class="td1"><input type="text" class="sspan" readonly="readonly" name="baozhuang"></td><td class="td1"><input type="text" class="textlonsl ypnum" value="1" name="shuliang"><span class="sldw"></span></td><td class="td1"><input type="text" class="sspan" readonly="readonly" name="zongliang"></td><td class="td1"><select class="textlon tuj" name="tujing">'+str+'</select></td><td class="td1"><input type="text" class="textlonsl ypcyl" value="1" name="yongliang"><span class="ylxdw"></span></td><td class="td1"><select class="textlon pinl" name="cishu">'+plstr+'</select></td><td class="td1"><select name="tsyf" class="textlon yongf"><option value="0">无</option><option value="1">皮试</option><option value="2">小壶</option></select></td><td class="td1"><input type="text"  class="textlon ypdays sspan" readonly="readonly" name="tianshu"></td></tr>');
		
		}
	 }
	 }
		$('.tr2:last').find('td').eq(2).find('input').focus();
	});



	//保存处方
	


	//输入药品名查询框
		// $(document).on("input",".ypname",function(){
		// 		var htm = $(this).val();
		// 		var top = $(this).offset().top 
		// 		var left = $(this).offset().left
		// 		top = top-40;
		// 		left = left -110;
		// 		$('#aja').css('top',top+'px');
		// 		$('#aja').css('left',left+'px');
		// 		 $.ajax({
  //           type:'POST',
  //           url:"__MODULE__/Home/Ajax/drugWest",
  //           data:{htm:htm},
  //           dataType:'json',
  //           success:function(dd)
  //           {
  //               alert(dd);
  //               $.each(dd,function(idx,item){
  //               //输出
  //               // alert(item.id+"号："+item.name);
              
  //               });
               
  //           },
  //           error:function()
  //           {
  //               alert('Ajax请求失败');
  //           }
  //       });
		// 		$('#aja').html($(this).val());
		// 		$('#aja').fadeIn();
		// 		$(this).blur(function(){
		// 		$('#aja').fadeOut();
		// 	});
		// 	});
		// 	
		//西药名称 	
		$(document).on('mouseover','.westtr',function(){
		$(this).css('backgroundColor','#A8CBF1').css('color','#fff');
		// $(this).click(function(){
			
			// if($(this).find(' td:nth-child(2)').find('.che').is(':checked')){
			// 	$(this).find(' td:nth-child(2)').find('input').attr('checked',false);
			// }else{
			// 	$(this).find(' td:nth-child(2)').find('input').attr('checked',true);
				
			// }
			// xybma = $(this).children().first().html();
			// $('#a123').html(xybma);
			// $('#exit').click();
		// });
	}).on('mouseout','.westtr',function(){
		$(this).css('backgroundColor','').css('color','');;
	});
});