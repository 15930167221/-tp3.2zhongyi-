/**
 * mkcatalogue
 * 
 * 生成目录
 */

var dom = {
	on: function(node, type, callback) {
		if (node.addEventListener) {
			node.addEventListener(type, callback);
		} else if (node.attachEvent) {
			node.attachEvent('on'+type, callback);
		} else {
			node['on'+type] = callback;
		}
	}
};

//var isIE8 = platform.name === 'IE' && platform.version === '8.0';


 	function CMakeCatalogue(contentNode,catalogueNode){
 		this.contentNode = contentNode||document.body;		//内容节点
 		this.catalogueNode = catalogueNode||document.body;			//目录节点
 		//创建节点缓存
 		this.cacheContent = document.createDocumentFragment();
 		this.cacheCatalogue=document.createDocumentFragment();

 		this.positionMap = {};
 		this.positionList= [];
 		this.initCatalogue();
 		this.parseContent();
 		this.bindEvent();
 	}

 	CMakeCatalogue.prototype.initCatalogue = function(){
 		//目录标题
 		var toctitle = document.createElement('div');
 		toctitle.id = "toctitle";
 		var header = document.createElement('h1');
 		header.innerHTML = '目录';
 		toctitle.appendChild(header);
 		var span = document.createElement('span');
 		span.id = 'toctoggle';
 		span.innerHTML = '隐藏';
 		toctitle.appendChild(span);
 		this.cacheCatalogue.appendChild(toctitle); 	
 		//目录内容
 		var toccnt = document.createElement('div');
 		toccnt.id = 'toccnt'
 		var ul = document.createElement('ul');
 		toccnt.appendChild(ul);	
		this.cacheCatalogue.appendChild(toccnt);
		this.tarUl = ul;		//生成目录目标ul 
		this.curIndex = 0;
		this.curObj = {};		
 	}

 	CMakeCatalogue.prototype.parseContent = function(){		//解析内容模板
 		var srcTitleH1 = this.contentNode.getElementsByTagName('h1')[0];
 		if(srcTitleH1){
 			// document.body.insertBefore(srcTitleH1,document.getElementById('content'));
 			document.title = srcTitleH1.innerHTML + '_中医健康管理系统';
 		}

		var srcTitleH2 = this.contentNode.getElementsByTagName('h2');
		var tarUl = this.tarUl;
 		var len = srcTitleH2.length
 		for(i=0;i<len;i++){				//遍历二级标题

 			var span = null, targ = null;
 			var tmpCatalogueToggle = null;

 			var srch2 = srcTitleH2[i];						//源h2节点
 			srch2.id = 'src-cnt-'+i;
 			//生成目录单元
 			var tmpCatalogueTarget = this.makeCatalogueUnit(i,this.parseCatalogueNode(srch2.innerHTML),'h2')

 			//重写正文二级标题
 			tarUl.appendChild(tmpCatalogueTarget);
 			srch2.innerHTML = this.parseContentNode(srch2.innerHTML);
 			this.positionList.push({
 				'pos':parseInt(this.calContentHeight($('#'+srch2.id).offset().top)),
 				'id' :srch2.id
 			});
 			
 			//建立三级标题目录列表container
 			var tardiv = document.createElement('div');
 			tardiv.id = 'toc-cnt-'+i;
 			tardiv.className = 'toc-cnt-list';
 			tarUl.appendChild(tardiv);
 			this.positionMap[srch2.id.replace('src-cnt','toc-ttl')] = {};
			this.positionMap[srch2.id.replace('src-cnt','toc-ttl')]['pos'] = parseInt(this.calContentHeight($('#'+srch2.id).offset().top));
			this.positionMap[srch2.id.replace('src-cnt','toc-ttl')]['id']  = this.calContentNodeId(srch2.innerHTML);

 		 	var nextSibl = srch2;
 		 	var j = 0;		//三级标题计数
 		 	var k = 0;		//四级标题计数
 		 	while(nextSibl){				//查找三/四级标题
 		 		nextSibl = nextSibl.nextSibling;
 				if(!nextSibl){
 					break;
 				}else if(typeof nextSibl.tagName=='undefined'){
 					continue;
 				}else if(nextSibl.tagName.toLowerCase()=='h2'){
 					if(j>0){
 						tmpCatalogueToggle = document.createElement('span');
 						tmpCatalogueToggle.className = 'f-toggle f-toggle-plus';
 						tmpCatalogueTarget.insertBefore(tmpCatalogueToggle,tmpCatalogueTarget.firstChild);
 					//	tmpCatalogueTarget.removeChild(tmpCatalogueToggle);
 					}
 					break;
 				}else if(nextSibl.tagName.toLowerCase()=='h3'){		//三级标题
 					var srch3 = nextSibl;
 					srch3.id = 'src-cnt-'+i+'-'+j;
 					targ = this.makeCatalogueUnit(i+'-'+j,this.parseCatalogueNode(srch3.innerHTML),'h3')	//左侧链接导航
 					tardiv.appendChild(targ);
 					srch3.innerHTML = this.parseContentNode(srch3.innerHTML);
		 			j++;
		 			this.positionList.push({
		 				'pos':parseInt(this.calContentHeight($('#'+srch3.id).offset().top)),
		 				'id' :srch3.id
		 			});		
		 			this.positionMap[srch3.id.replace('src-cnt','toc-ttl')] = {}; 
		 			this.positionMap[srch3.id.replace('src-cnt','toc-ttl')]['pos'] = parseInt(this.calContentHeight($('#'+srch3.id).offset().top));			
 					this.positionMap[srch3.id.replace('src-cnt','toc-ttl')]['id']  = this.calContentNodeId(srch3.innerHTML);
 					k = 0;
 				}else if(nextSibl.tagName.toLowerCase()=='h4'){		//四级标题
 					var srch4 = nextSibl;
 					srch4.id = 'src-cnt-'+i+'-'+(Math.max(j-1,0))+'-'+k;
 					srch4.innerHTML = this.parseContentNode(srch4.innerHTML);
 					k++;
 					this.positionList.push({
		 				'pos':parseInt(this.calContentHeight($('#'+srch4.id).offset().top)),
		 				'id' :srch4.id
		 			});		
		 			this.positionMap[srch4.id.replace('src-cnt','toc-ttl')] = {}; 
		 			this.positionMap[srch4.id.replace('src-cnt','toc-ttl')]['pos'] = parseInt(this.calContentHeight($('#'+srch4.id).offset().top));			
 					this.positionMap[srch4.id.replace('src-cnt','toc-ttl')]['id']  = this.calContentNodeId(srch4.innerHTML);	
 				}else if(nextSibl.tagName.toLowerCase()=='table'){
 					nextSibl.border = 1;
 					//nextSibl.cellspacing=0;				
 				}
 		 	}
 		}
		
		if(j>0){		//补充尾部下拉按钮
			tmpCatalogueToggle = document.createElement('span');
			tmpCatalogueToggle.className = 'f-toggle f-toggle-plus';
			tmpCatalogueTarget.insertBefore(tmpCatalogueToggle,tmpCatalogueTarget.firstChild);
		//	tmpCatalogueTarget.removeChild(tmpCatalogueToggle);
		}

 		var preList = document.getElementsByTagName('pre');
 		if(preList && preList.length>0){
 			for(var i=0;i<preList.length;i++){
 				var preNode = preList[i];
 				if(preNode.firstChild.tagName.toLowerCase()=='code'){
					span = document.createElement('span');
					span.className = "u-copy-btn";
					span.appendChild(document.createTextNode('复制'));
					span.title = "点击复制代码"
					preNode.insertBefore(span,preNode.firstChild);
 				}
 			}
 		} 		
 		//console.log(this.positionMap);
 		this.positionList.sort(function(a,b){
 			return a.pos - b.pos;
 		})
 		//console.log(this.positionList);
 		this.catalogueNode.appendChild(this.cacheCatalogue)
 		$('.toc-cnt-list').hide();
 		this.calInterAction()

 	}

 	//计算段落标题相应高度
 	CMakeCatalogue.prototype.calContentHeight =function(i){
 		return i-80;
 	}

 	//解析文章节点，带id
 	CMakeCatalogue.prototype.parseContentNode = function(value){
 		var _t = /(?:&gt;|&gt|>)(.*)(?:&lt;|&lt|<)\/[\w]+/.exec(value);
 		if(_t){
 			var id = /id=[\'\"]([^\'\"]+)[\'\"']/.exec(value);
 			if(!id){
 				id = /id=([^\'\"\s\>]+)/.exec(value);
 			}
 			if(id){
 				id = id[1];
 			}else{
 				id = _t[1];
 			}
 			value = _t[1];

 			return '<span id=\''+id+'\'>'+value+'</span>';
 		}
 		var tmp = value.split('#');
 		if(tmp[1]){
 			return '<span id=\''+tmp[1]+'\'>'+tmp[0]+'</span>';
 		}
 		return value;
 	}

 	CMakeCatalogue.prototype.calContentNodeId = function(value){
 		var _t = /(?:&gt;|&gt|>)(.*)(?:&lt;|&lt|<)\/[\w]+/.exec(value);
 		if(_t){
 			var id = /id=[\'\"]{0,1}([^\'\">]+)(?:[\'\"']|>)/.exec(value);
 			if(id){
 				id = id[1];
 			}else{
 				id = '';
 			}
 			return id;
 		}
 		var tmp = value.split('#');
 		if(tmp[1]){
 			return tmp[1];
 		}
 		return "";
 	}

 	//生成左侧目录节点
 	CMakeCatalogue.prototype.parseCatalogueNode = function(value){
 		var _t = /(?:&gt;|&gt|>)(.*)(?:&lt;|&lt|<)\/[\w]+/.exec(value);
 		if(_t){
 			value = _t[1];
 			return value;
 		}
 		var tmp = value.split('#');
 		if(tmp[1]){
 			return tmp[0];
 		}
 		return value;
 	}

 	CMakeCatalogue.prototype.makeCatalogueUnit = function(id,value,nodeType){
 		var li = document.createElement('li');
 		li.id = 'toc-ttl-'+id;
 		var tarh2 = document.createElement(nodeType);
 		if(value.indexOf('#')>0 && value.indexOf('C#')<0){
 			var tmp = value.split('#');
 			if(tmp[1]){
 				// var span = document.createElement('span');
 				// span.id = tmp[0];
 				// span.innerHTML = tmp[0];
 				// tarh2.innerHTML = '';
 				// tarh2.appendChild(span);
 				tarh2.innerHTML = tmp[0];
 			}else{
 				tarh2.innerHTML = value;
 			}
 		}else{
 			tarh2.innerHTML = value;				//目录h2节点
 		}
 		li.appendChild(tarh2)
 		return li;
 	}

 	CMakeCatalogue.prototype.bindEvent = function(){
 		var that = this;
 		// window.onscroll = function(e){
 		// 	that.calInterAction();
 		// }
 		that.scrollTimer = null;
 		dom.on(window, 'scroll', function(e) {
 			that.calInterAction();	
 			var tempHash = location.hash.replace('#','');		//ie8 直接在地址栏回车修复		
			for(var id in that.positionMap){
				if(that.positionMap[id].id==tempHash){
					break;	
				}
			}
			if(that.positionMap[id].id==tempHash && (!(-[1,])||window.msPerformance)){
				clearTimeout(that.scrollTimer);
				that.scrollTimer = setTimeout(function(){
		 			var scrollTop;
				    if (typeof window.pageYOffset != 'undefined') { //pageYOffset指的是滚动条顶部到网页顶部的距离
				        scrollTop = window.pageYOffset;
				    } else if (typeof document.compatMode != 'undefined' && document.compatMode != 'BackCompat') {
				        scrollTop = document.documentElement.scrollTop;
				    } else if (typeof document.body != 'undefined') {
				        scrollTop = document.body.scrollTop;
				    }
					var pos = that.positionMap[id].pos;	
					var delta = (pos-scrollTop);	
					//console.log(delta);				
					if(  (delta>-124&&delta<-116) ){
						$('body,html').animate({scrollTop:pos},0,'swing',function(){});							
					}
				},200);

			}

 		});
 		$('#toccnt li').click(function(e){
 			var target = e.target;
 			if(target.tagName.toLowerCase()=='span'){
 				var result = /f-toggle-(\w+)$/.exec(target.className);
 				if(!!result){
					var sublistId = target.parentNode.nextSibling.id;
					// $('#'+sublistId).toggle(200);
 					if(result[1]=='plus'){			//展开列表
 						target.className = 'f-toggle f-toggle-minus';
 						$('#'+sublistId).show(200);
 						// target.parentNode.nextSibling.style.display = 'block';
 					}else if(result[1]=='minus'){	//收拢列表
 						target.className = 'f-toggle f-toggle-plus';
 						$('#'+sublistId).hide(200);
 						// target.parentNode.nextSibling.style.display = 'none';
 					}

 					e.stopPropagation();
 				}
 				return;
 			}else if(target.tagName.toLowerCase()!='li'){
 				target = target.parentNode;
 			}
 			if(target.tagName.toLowerCase()!='li')
 				target = target.parentNode
 			id = target.id;
 			if(that.positionMap[id]){
 				//console.log(that.positionMap[id])
				if ('pushState' in history){
					var searchUrl = location.href;
					var key = searchUrl.indexOf('?');
					searchUrl = searchUrl.substring(0,key);
					if(that.positionMap[id]['id']){
						var searchTmp = that.calUrlSearch('');
						history.replaceState(target.id,'',searchUrl+searchTmp+'#'+that.positionMap[id]['id']);
					}else{
						var searchTmp = that.calUrlSearch(id);
						history.replaceState(target.id,'',searchUrl+searchTmp);
					}
					$('body,html').animate({scrollTop:that.positionMap[id]['pos']},200,'swing',function(){
						that.interAction(id.replace('toc-ttl','src-cnt'));
					}); 
				}else{
					if(that.positionMap[id]['id']){		//存在对应id列表
						var tempHash = location.hash;
						if(tempHash === ('#'+that.positionMap[id]['id'])){
							return;
						}else if(that.positionMap[id]['id']!=''){
							location.hash = ('#'+that.positionMap[id]['id']);
						}
					}else{								//不存在对应id列表
						var tempHash = location.hash;
						if(!tempHash||tempHash=='#'){
							return;
						}
						// location.hash = ('#');
						// $('body,html').animate({scrollTop:that.positionMap[id]['pos']},200,'swing',function(){
						// 	that.interAction(id.replace('toc-ttl','src-cnt'));
						// }); 
					}					
				}
				//console.log(that.positionMap[id])
 			}	
 		});

		//复制到剪贴板
		// $(".u-copy-btn").zclip({
		// 	path: "/javascripts/ZeroClipboard.swf",
		// 	copy: function(){
		// 		var copytext = $(this).next().text();
		// 		return copytext;
		// 	},
		// 	beforeCopy:function(){/* 按住鼠标时的操作 */
		// 		$(this).css('top','22px');
		// 		$(this).css('right','13px');
		// 		$(this).css('color','#932c06');
		// 		$(this).next().css('background-color','#e7e3f2');

		// 	},
		// 	afterCopy:function(){/* 复制成功后的操作 */
		// 		$(this).css('top','20px');
		// 		$(this).css('right','15px');
		// 		$(this).css('color','#bf3907');
		// 		$(this).next().css('background-color','#f5f5ff');
	 //        }
		// });
		glueCopy();		//复制按钮事件

		window.onpopstate = function(event){
			if(location.hash.length>1){
				//根据hash定位
				try{
					var target = document.getElementById(location.hash.replace('#',''));
					target = target.parentNode;
					var id = target.id;
					if(/^src-cnt-/.test(id)){
						id = id.replace('src-cnt-','toc-ttl-');
					}
					$('body,html').animate({scrollTop:that.positionMap[id]['pos']},200); 
				}catch(e){

				}
			}else{
				try{
					var id = event.state;
					document.getElementById(id).click();				
				}catch(e){

				}				
			}

		}
 		// $('#toctoggle').click(function(){
 		// 	$('#toccnt').toggle(300)
 		// });
 	}

 	function glueCopy() {
 		ZeroClipboard.config( { 
 			swfPath: "/javascripts/ZeroClipboard.swf",
 			forceHandCursor: true
 		} );
	    $(function() {
	    	var clip = new ZeroClipboard( $(".u-copy-btn") );
			clip.on("ready", function() {
			    console.log("Copy flash movie loaded and ready.");
			});

			clip.on("beforecopy", function(event) {
  				var target = event.target;
  				target.style.top  ='22px';
				target.style.right='16px';
				target.style.color='#932c06';
				target.nextSibling.style.backgroundColor='#e7e3f2';
			});

			clip.on("copy", function(event) {
				var target = event.target;
				var copytext = target.nextSibling.textContent || target.nextSibling.innerText || target.nextSibling.innerHTML;
				var clipboard = event.clipboardData;
  				clipboard.setData( "text/plain", copytext );
			});

		    clip.on("aftercopy", function(event) {
		      	// console.log("Copied text to clipboard: " + event.data["text/plain"]);
  				var target = event.target;
  				target.style.top  ='20px';
				target.style.right='18px';
				target.style.color='#bf3907';
				target.nextSibling.style.backgroundColor='#f5f5ff';
		    });

			clip.on("error", function(event) {
			    $(".u-copy-btn").hide();
			    console.log('error[name="' + event.name + '"]: ' + event.message);
			    ZeroClipboard.destroy();
			});

	    })
	}

 	CMakeCatalogue.prototype.calUrlSearch = function(id){
		var searchUrl = location.search.substr(1);
		searchUrl = searchUrl.split('&');
		var searchTmp = '?';
		for(var key=0;key<searchUrl.length;key++){
			if(searchUrl[key].indexOf('pos=')>=0){
				//searchTmp += 'pos='+id.replace('-ttl','')+'&';
			}else if(searchUrl[key]==''){

			}else{
				searchTmp += searchUrl[key]+'&'
			}
		}
		if(id!=''){
			searchTmp += 'pos='+id.replace('-ttl',''); 	
		}
		return searchTmp;
 	}

 	CMakeCatalogue.prototype.calInterAction = function(){
 			var scrollTop;
		    if (typeof window.pageYOffset != 'undefined') { //pageYOffset指的是滚动条顶部到网页顶部的距离
		        scrollTop = window.pageYOffset;
		    } else if (typeof document.compatMode != 'undefined' && document.compatMode != 'BackCompat') {
		        scrollTop = document.documentElement.scrollTop;
		    } else if (typeof document.body != 'undefined') {
		        scrollTop = document.body.scrollTop;
		    }

 			this.curIndex = this.search(this.positionList,scrollTop+80);		//查找位置对应id
 			this.curObj = this.positionList[this.curIndex];
 			if(typeof this.curObj!='undefined'){
 				if(/src-cnt-[\d]+-[\d]+-[\d]+/.test(this.curObj.id)){
 					var result = /(src-cnt-[\d]+-[\d]+)-[\d]+/.exec(this.curObj.id);
 					this.interAction(result[1]);
 				}else{
 					this.interAction(this.curObj.id);
 				}
 			}else{
 				this.interAction('src-cnt-0');
 			}
 	}

 	//交互显示左侧标题
 	CMakeCatalogue.prototype.interAction = function(id){
 		$('#toccnt li').removeClass('f-sel');
 		if(/^src-cnt-[\d]+$/.test(id)){				//一级标题
 			$('.toc-cnt-list').hide();
 			var nid = id.replace('src','toc');
 			$('#'+nid).show();						//显示子标题
 			nid = id.replace('src-cnt','toc-ttl');
 			$('#toccnt li').removeClass('f-hsel');
 			$('#'+nid).addClass('f-sel');
 			$('#'+nid).addClass('f-hsel');
			if(/^toc-ttl-\d+$/.test(nid) ){		//一级标题加号减号变化
				// $('.f-toggle').removeClass('f-toggle-minus');
				// $('.f-toggle').addClass('f-toggle-plus');
				if( $('#'+nid).children('span').hasClass('f-toggle') ){
					($('#'+nid).children()[0]).className = 'f-toggle f-toggle-minus';
				}
			}
 		}else{
 			$('.toc-cnt-list').hide();
 			var nid = /^src-(cnt-[\d]+)-[\d]+$/.exec(id);
 			if(!!nid){
 				nid = 'toc-'+nid[1];		//二级标题
 				var nid_type = 2;
 			}else{
 				var nid = /^src-(cnt-[\d]+)-[\d]+-[\d]+$/.exec(id);
 				nid = 'toc-'+nid[1];
 				var nid_type = 3;			//三级标题
 			}
 			$('#'+nid).show();						//显示子标题列表
 			$('#toccnt li').removeClass('f-hsel');
 			nid = nid.replace('cnt','ttl');
 			$('#'+nid).addClass('f-hsel');	
 			//一级标题加号变减号
 			$('.f-toggle').removeClass('f-toggle-minus');
			$('.f-toggle').addClass('f-toggle-plus');
			if( $('#'+nid).children('span').hasClass('f-toggle') ){
 				($('#'+nid).children()[0]).className = 'f-toggle f-toggle-minus';
 			}

 			nid = id.replace('src-cnt','toc-ttl');		//二级标题高亮
 			if(nid_type==2){
 				$('#'+nid).addClass('f-sel');	
 			}else{
 				nid = /(toc-ttl-[\d]+-[\d]+)/.exec(nid);
 				nid = nid[0];
 				$('#'+nid).addClass('f-sel');	
 			}
 		}

 	}

 	//二分查找
 	CMakeCatalogue.prototype.search = function(array,value){
 		var tmpArr = array.slice();
 		var indexL=0, indexH=tmpArr.length-1, indexM = parseInt(indexH/2);
 		while(indexL<indexH){
 			indexM = parseInt((indexL+indexH)/2)
 			if(value==tmpArr[indexM].pos){
 				indexL = indexM;
 				break;
 			}else if(value<tmpArr[indexM].pos){
 				indexH = indexM-1;
 			}else if(value>tmpArr[indexM].pos){
 				indexL = indexM+1;
 			}
 		}
 		if(tmpArr[indexL].pos>value){
 			return indexL-1;
 		}else{
 			return indexL;
 		}
 	}