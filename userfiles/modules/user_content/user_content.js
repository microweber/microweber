// JavaScript Document

mw.user_content = {} 

mw.user_content.edit = function(content_id,callback){
var params = {}
params.content_id = content_id;

var output_div = '#mw-user-content-add-edit-item-holder';

mw.load_module('user_content/edit',output_div,callback,params);
}