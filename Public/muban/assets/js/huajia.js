//2017/02/15
//选中tr变颜色
var id = 0;
$(".tab4_1").delegate("tr","click",function(){
	var tsty1 = document.getElementsByName("tableSty");
	for(var i=0;i<tsty1.length;i++){
	    tsty1[i].className="sty1";
	}
	$(this).attr("class","sty2");
	id = (this.rowIndex) + 1;
	var chufangid = $(this).attr('id');
})

//删除按钮
function doFun(x){
	var sD=document.getElementById("Dshow");
	if(id == 0){/*判断是否选中一行*/
		return false;
	}else{
		if(x == "doClose"){
			var len = $(".tab4_1").find("tr").length;
			var tab = $(".tab4_1 tr"); 
			for(var i=1;i<=len;i++){
				if(i == id){
					tab[(i-1)].remove();//删除选中行
					sD.style.display = "none";
					var len1 = $(".tab4_1").find("tr").length;
					var value = 0.00;
					if(len1 == 0){//收费列表为空
						value = value;
					}else{
						for(var k=1;k<=len1;k++){//改变序号并更改合计金额
							var str = "<input type='hidden' id='xuhao' name='xuhao["+k+"]' value='"+k+"'>"+k;
							$(".tab4_1 tr:nth-child("+k+")").children('td:eq(0)').html(str);
							var value1 = Number($(".tab4_1 tr:nth-child("+k+")").children('td:last').text());
							value = (Number(value) + Number(value1)).toFixed(2);
							var str1="xmname["+k+"]";
							var str2="danwei["+k+"]";
							var str3="danjia["+k+"]";
							var str4="number["+k+"]";
							var str5="jine["+k+"]";
							$(".tab4_1 tr:nth-child("+k+")").children("td").eq(1).children("input").attr("name",str1);
							$(".tab4_1 tr:nth-child("+k+")").children("td").eq(2).children("input").attr("name",str2);
							$(".tab4_1 tr:nth-child("+k+")").children("td").eq(3).children("input").attr("name",str3);
							$(".tab4_1 tr:nth-child("+k+")").children("td").eq(4).children("input").attr("name",str4);
							$(".tab4_1 tr:nth-child("+k+")").children("td").eq(5).children("input").attr("name",str5);
						}
					}
					$(".tab3 tr td:last").text('￥'+value);
			        $(".tab3 tr td:last").css("color","#30BEB2");
			        $(".tab3 tr td:last").css("font-weight","bold");
			        $(".tab3 tr td:last").css("font-size","20px");
				}				
			}
		}	
	}
	if(x == "doShow"){
		sD.style.display = "block";
	}
	if(x == "returnFalse"){
		sD.style.display = "none";
	}
}

//收费保存数据库
function sub(){
	if($(".tab4_1 tr").length == 0){
		return false;
	}else{
	 	var int = document.getElementsByTagName('input');
	 	var hidArr = [];
	 	var Arr = [];
	 	for(var i=0;i<int.length;i++){
	 		if(int[i].type == 'hidden'){
	 			hidArr.push(int[i]);
	 		}
	 	}
	 	/*for(var key in hidArr){
	 		document.writeln(hidArr[key].value);
	 	}*/
		$("#shoufei").submit();
	}
}

function chaxun(){
	$("#chaxun").submit();
}

