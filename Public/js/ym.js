$(function(){
    //页面全是动态生成的div此处用1,html页面初始化时用0,如果页面有静态的div则反过来
    InitSuggest($(".taobao")[1]);
});

function InitSuggest(element){
    $(element).bsSuggest({
        //indexId: 2,             //data.value 的第几个数据，作为input输入框的内容
        //indexKey: 3,            //data.value 的第几个数据，作为input输入框的内容
        allowNoKeyword: false,  //是否允许无关键字时请求数据。为 false 则无输入时不执行过滤请求
        multiWord: true,        //以分隔符号分割的多关键字支持
        separator: ",",         //多关键字支持时的分隔符，默认为空格
        getDataMethod: "url",   //获取数据的方式，总是从 URL 获取
        showHeader: true,       //显示多个字段的表头
        autoDropup: true,       //自动判断菜单向上展开
        effectiveFieldsAlias:{ drug_name: "药品名称",xw: "性味",other_name: "正名"},
        url: 'http://www.mirazyzl.com:8088/index.php/Home/Api/jingYanajax?p=', /*优先从url ajax 请求 json 帮助数据，注意最后一个参数为关键字请求参数*/

        jsonp: 'callback',               //如果从 url 获取数据，并且需要跨域，则该参数必须设置
        // url 获取数据时，对数据的处理，作为 fnGetData 的回调函数
        fnProcessData: function(json){
            //console.log(json);
            var index, len, data = {value: []};

            if(! json || ! json.result || ! json.result.length) {
                return false;
            }

            len = json.result.length;

            for (index = 0; index < len; index++) {
                data.value.push({
                    "drug_name": json.result[index]['name'],
                    "xw": json.result[index]['xw'],
                    "other_name": json.result[index]['other_name']
                });
            }
            //console.log('药名: ', data);
            return data;
        }
    }).on('onDataRequestSuccess', function (e, result) {
        console.log('onDataRequestSuccess: ', result);
    }).on('onSetSelectValue', function (e, keyword, data) {
        console.log('onSetSelectValue: ', keyword, data);
    }).on('onUnsetSelectValue', function () {
        console.log("onUnsetSelectValue");
    });
}