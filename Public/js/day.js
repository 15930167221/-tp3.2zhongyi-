// 处方天数计算
function days(){
	var cyl = $('.del:first').find('td').eq(9).find('input').val();
	var hl = $('.del:first').find('td').eq(4).find('input').val();
	var rcs = $('.del:first').find('td').eq(10).find('select').val();
	var sl = $('.del:first').find('td').eq(6).find('input').val();
	var bzxs = $('.del:first').find('td').eq(7).find('input').val();
	// var nu1 = cyl/hl/rcs;
	if(cyl == ''){
		return false;
	}
	if(hl == ''){
		return false;
	}
	if(rcs == ''){
		return false;
	}
	if(sl == ''){
		return false;
	}
	if(bzxs == ''){
		return false;
	}
	var n1 = sl*bzxs;
	var n2 = cyl/hl;
	var days = Math.round(n1/n2*rcs);
	if(isNaN(days)){
		days = '数据有误';
	}
	$('.del').find('td:last').find('input').val(days);
}