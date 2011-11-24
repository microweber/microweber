/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.util.TaskRunner=function(E){E=E||10;var F=[],A=[];var B=0;var G=false;var D=function(){G=false;clearInterval(B);B=0};var H=function(){if(!G){G=true;B=setInterval(I,E)}};var C=function(J){A.push(J);if(J.onStop){J.onStop.apply(J.scope||J)}};var I=function(){if(A.length>0){for(var O=0,K=A.length;O<K;O++){F.remove(A[O])}A=[];if(F.length<1){D();return }}var M=new Date().getTime();for(var O=0,K=F.length;O<K;++O){var N=F[O];var J=M-N.taskRunTime;if(N.interval<=J){var L=N.run.apply(N.scope||N,N.args||[++N.taskRunCount]);N.taskRunTime=M;if(L===false||N.taskRunCount===N.repeat){C(N);return }}if(N.duration&&N.duration<=(M-N.taskStartTime)){C(N)}}};this.start=function(J){F.push(J);J.taskStartTime=new Date().getTime();J.taskRunTime=0;J.taskRunCount=0;H();return J};this.stop=function(J){C(J);return J};this.stopAll=function(){D();for(var K=0,J=F.length;K<J;K++){if(F[K].onStop){F[K].onStop()}}F=[];A=[]}};Ext.TaskMgr=new Ext.util.TaskRunner();