//forms
;(function($){
	$.fn.forms=function(o){
		return this.each(function(){
			var th=$(this)
				,_=th.data('forms')||{
					errorCl:'error',
					emptyCl:'empty',
					invalidCl:'invalid',
					notRequiredCl:'notRequired',
					successCl:'success',
					successShow:'4000',
					mailHandlerURL:'js/owlmail.php',
					ownerEmail:'owltemplates.com@gmail.com',
					stripHTML:true,
					smtpMailServer:'localhost',
					targets:'input,textarea',
					controls:'a[data-type=reset],a[data-type=submit]',
					validate:true,
					rx:{
						".name":{rx:/^[a-zA-Z'][a-zA-Z-' ]+[a-zA-Z']?$/,target:'input'},
						".state":{rx:/^[a-zA-Z'][a-zA-Z-' ]+[a-zA-Z']?$/,target:'input'},
						".email":{rx:/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i,target:'input'},
						".phone":{rx:/^\+?(\d[\d\-\+\(\) ]{5,}\d$)/,target:'input'},
						".fax":{rx:/^\+?(\d[\d\-\+\(\) ]{5,}\d$)/,target:'input'},
						".message":{rx:/.{20}/,target:'textarea'}
					},
					preFu:function(){
						_.labels.each(function(){
							var label=$(this),
								inp=$(_.targets,this),
								defVal=inp.val(),
								trueVal=(function(){
											var tmp=inp.is('input')?(tmp=label.html().match(/value=['"](.+?)['"].+/),!!tmp&&!!tmp[1]&&tmp[1]):inp.html()
											return defVal==''?defVal:tmp
										})()
							trueVal!=defVal
								&&inp.val(defVal=trueVal||defVal)
							label.data({defVal:defVal})								
							inp
								.bind('focus',function(){
									inp.val()==defVal
										&&(inp.val(''),_.hideEmptyFu(label),label.removeClass(_.invalidCl))
								})
								.bind('blur',function(){
									_.validateFu(label)
									if(_.isEmpty(label))
										inp.val(defVal)
										,_.hideErrorFu(label.removeClass(_.invalidCl))											
								})
								.bind('keyup',function(){
									label.hasClass(_.invalidCl)
										&&_.validateFu(label)
								})
							label.find('.'+_.errorCl+',.'+_.emptyCl).css({display:'block'}).hide()
						})
						_.success=$('.'+_.successCl,_.form).hide()
					},
					isRequired:function(el){							
						return !el.hasClass(_.notRequiredCl)
					},
					isValid:function(el){							
						var ret=true
						$.each(_.rx,function(k,d){
							if(el.is(k))
								ret=d.rx.test(el.find(d.target).val())										
						})
						return ret							
					},
					isEmpty:function(el){
						var tmp
						return (tmp=el.find(_.targets).val())==''||tmp==el.data('defVal')
					},
					validateFu:function(el){							
						el.each(function(){
							var th=$(this)
								,req=_.isRequired(th)
								,empty=_.isEmpty(th)
								,valid=_.isValid(th)								
							
							if(empty&&req)
								_.showEmptyFu(th.addClass(_.invalidCl))
							else
								_.hideEmptyFu(th.removeClass(_.invalidCl))
							
							if(!empty)
								if(valid)
									_.hideErrorFu(th.removeClass(_.invalidCl))
								else
									_.showErrorFu(th.addClass(_.invalidCl))								
						})
					},
					getValFromLabel:function(label){
						var val=$('input,textarea',label).val()
							,defVal=label.data('defVal')								
						return label.length?val==defVal?'nope':val:'nope'
					}
					,submitFu:function(){
						_.validateFu(_.labels)							
						if(!_.form.has('.'+_.invalidCl).length)
							$.ajax({
								type: "POST",
								url:_.mailHandlerURL,
								data:{
									name:_.getValFromLabel($('.name',_.form)),
									email:_.getValFromLabel($('.email',_.form)),
									phone:_.getValFromLabel($('.phone',_.form)),
									fax:_.getValFromLabel($('.fax',_.form)),
									state:_.getValFromLabel($('.state',_.form)),
									message:_.getValFromLabel($('.message',_.form)),
									owner_email:_.ownerEmail,
									stripHTML:_.stripHTML
								},
								success: function(){
									_.showFu()
								}
							})			
					},
					showFu:function(){
						_.success.slideDown(function(){
							setTimeout(function(){
								_.success.slideUp()
								_.form.trigger('reset')
							},_.successShow)
						})
					},
					controlsFu:function(){
						$(_.controls,_.form).each(function(){
							var th=$(this)
							th
								.bind('click',function(){
									_.form.trigger(th.data('type'))
									return false
								})
						})
					},
					showErrorFu:function(label){
						label.find('.'+_.errorCl).slideDown()
					},
					hideErrorFu:function(label){
						label.find('.'+_.errorCl).slideUp()
					},
					showEmptyFu:function(label){
						label.find('.'+_.emptyCl).slideDown()
						_.hideErrorFu(label)
					},
					hideEmptyFu:function(label){
						label.find('.'+_.emptyCl).slideUp()
					},
					init:function(){
						_.form=_.me						
						_.labels=$('label',_.form)

						_.preFu()
						
						_.controlsFu()
														
						_.form
							.bind('submit',function(){
								if(_.validate)
									_.submitFu()
								else
									_.form[0].submit()
								return false
							})
							.bind('reset',function(){
								_.labels.removeClass(_.invalidCl)									
								_.labels.each(function(){
									var th=$(this)
									_.hideErrorFu(th)
									_.hideEmptyFu(th)
								})
							})
						_.form.trigger('reset')
					}
				}
			_.me||_.init(_.me=th.data({forms:_}))
			typeof o=='object'
				&&$.extend(_,o)
		})
	}
})(jQuery)
$(window).load(function(){
	$('#form1').forms({
		ownerEmail:'#'
	})
})