/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.QuickTips=function(){var B,A=[];return{init:function(C){if(!B){if(!Ext.isReady){Ext.onReady(function(){Ext.QuickTips.init(C)});return }B=new Ext.QuickTip({elements:"header,body"});if(C!==false){B.render(Ext.getBody())}}},enable:function(){if(B){A.pop();if(A.length<1){B.enable()}}},disable:function(){if(B){B.disable()}A.push(1)},isEnabled:function(){return B!==undefined&&!B.disabled},getQuickTip:function(){return B},register:function(){B.register.apply(B,arguments)},unregister:function(){B.unregister.apply(B,arguments)},tips:function(){B.register.apply(B,arguments)}}}();