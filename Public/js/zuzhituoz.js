/**
 * Created by dark on 2017/1/18.
 */

   $("input[type='text']").on("copy cut paste dragstart dragenter",function(e){
                    return false;
                });
   $("td").on("copy cut paste dragstart dragenter",function(e){
                    return false;
                });
$("input[type='text']").on("focus",function(e){
                    this.select();
                });
//删除历史记录
$("input[type='text']").attr("autocomplete","off");

