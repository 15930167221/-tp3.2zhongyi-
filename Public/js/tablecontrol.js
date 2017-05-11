/**
 * Created by Administrator on 2017/4/14.
 */
var currentLine=-1, offsetTr = 0;

function keyDownEvent(e){
    var e = window.event||e;
    if(e.keyCode==38){
        offsetTr = 0;
        currentLine--;
        changeItem();
    }else if(e.keyCode==40){
        offsetTr = 150;
        currentLine++;
        changeItem();
    }else if(e.keyCode==13&&currentLine>-1){
        addUser();
    }
    return false;
}
function changeItem(){

    if(!$('#dbtab_5')) return false;
    var it = $('#dbtab_5');
    if(document.all){
        it = $('#dbtab_5').children[0];
    }else
        var it = document.getElementById("dbtab_5");
    //changeBackground();
    if(currentLine<0) currentLine = it.rows.length-1;
    if(currentLine >= it.rows.length) currentLine = 0;
    alert(it.rows[currentLine].className);
    it.rows[currentLine].className = "buddyListHighLight";
    if($('#tb6')){
        $('#tb6').scrollTop = it.rows[currentLine].offsetTop-offsetTr;
    }
}
function changeBackground(){
    var it = $('#dbtab_5');
    if(document.all){
        it = $('#dbtab_5').children[0];
    }else
        var it = document.getElementById("dbtab_5");
    for(var i=0; i<it.rows.length; i++){
        if(i%2==0){
            it.rows[i].className = "buddyListOdd";
        }else{
            it.rows[i].className = "buddyListEven";
        }
    }
}
function addUser(){
    var it = $('#dbtab_5');
    if(document.all){
        it = $('#dbtab_5').children[0];
    }
    var trBody = it.rows[currentLine].innerHTML;
    $('#result').innerHTML = $('#result').innerHTML+trBody;
}
