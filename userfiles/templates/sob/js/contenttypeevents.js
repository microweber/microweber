
var postSetBinds = "mouseup";


/* ONSELECT */

var onSelectTraining = function(callback){
    $(window).load(function(){
        if($("#postSet").val()=='trainings'){
          callback.call(this);
        }
    });
    $("#addnav a").bind(postSetBinds, function(){
        if($("#postSet").val()=='trainings'){
          callback.call(this);
        }
    });
}
var onSelectArticle = function(callback){
    $(window).load(function(){
        if($("#postSet").val()=='none'){
          callback.call(this);
        }
    });
    $("#addnav a").bind(postSetBinds, function(){
        if($("#postSet").val()=='none'){
          callback.call(this);
        }
    });
}

var onSelectProduct = function(callback){
    $(window).load(function(){
        if($("#postSet").val()=='products'){
          callback.call(this);
        }
    });
    $("#addnav a").bind(postSetBinds, function(){
        if($("#postSet").val()=='products'){
          callback.call(this);
        }
    });
}

var onSelectService = function(callback){
    $(window).load(function(){
        if($("#postSet").val()=='services'){
          callback.call(this);
        }
    });
    $("#addnav a").bind(postSetBinds, function(){
        if($("#postSet").val()=='services'){
          callback.call(this);
        }
    });
}

var onSelectGallery = function(callback){
    $(window).load(function(){
        if($("#postSet").val()=='gallery'){
          callback.call(this);
        }
    });
    $("#addnav a").bind(postSetBinds, function(){
        if($("#postSet").val()=='gallery'){
          callback.call(this);
        }
    });
}
var onSelectBlog = function(callback){
    $(window).load(function(){
        if($("#postSet").val()=='blog'){
          callback.call(this);
        }
    });
    $("#addnav a").bind(postSetBinds, function(){
        if($("#postSet").val()=='blog'){
          callback.call(this);
        }
    });
}






/*
 *
 *
 ****************
 *  ONDESELECT  *
 ****************
 *
 *
 */

var onDeSelectTraining = function(callback){
    $(window).load(function(){
        if($("#postSet").val()!='trainings'){
          callback.call(this);
        }
    });
    $("#addnav a").bind(postSetBinds, function(){
        if($("#postSet").val()!='trainings'){
          callback.call(this);
        }
    });
}
var onDeSelectArticle = function(callback){
    $(window).load(function(){
        if($("#postSet").val()!='none'){
          callback.call(this);
        }
    });
    $("#addnav a").bind(postSetBinds, function(){
        if($("#postSet").val()!='none'){
          callback.call(this);
        }
    });
}

var onDeSelectProduct = function(callback){
    $(window).load(function(){
        if($("#postSet").val()!='products'){
          callback.call(this);
        }
    });
    $("#addnav a").bind(postSetBinds, function(){
        if($("#postSet").val()!='products'){
          callback.call(this);
        }
    });
}

var onDeSelectService = function(callback){
    $(window).load(function(){
        if($("#postSet").val()!='services'){
          callback.call(this);
        }
    });
    $("#addnav a").bind(postSetBinds, function(){
        if($("#postSet").val()!='services'){
          callback.call(this);
        }
    });
}

var onDeSelectGallery = function(callback){
    $(window).load(function(){
        if($("#postSet").val()!='gallery'){
          callback.call(this);
        }
    });
    $("#addnav a").bind(postSetBinds, function(){
        if($("#postSet").val()!='gallery'){
          callback.call(this);
        }
    });
}
var onDeSelectBlog = function(callback){
    $(window).load(function(){
        if($("#postSet").val()!='blog'){
          callback.call(this);
        }
    });
    $("#addnav a").bind(postSetBinds, function(){
        if($("#postSet").val()!='blog'){
          callback.call(this);
        }
    });
}