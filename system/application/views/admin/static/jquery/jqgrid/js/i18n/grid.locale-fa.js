;(function($){
/**
 * jqGrid Persian Translation
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
**/
$.jgrid = {
	defaults : {
		recordtext: "View {0} - {1} of {2}",
	    emptyrecords: "No records to view",
		loadtext: "بارگزاري...",
		pgtext : "Page {0} of {1}"
	},
	search : {
	    caption: "جستجو...",
	    Find: "يافته ها",
	    Reset: "نتايج",
	    odata : ['equal', 'not equal', 'less', 'less or equal','greater','greater or equal', 'begins with','does not begin with','is in','is not in','ends with','does not end with','contains','does not contain'],
	    groupOps: [	{ op: "AND", text: "all" },	{ op: "OR",  text: "any" }	],
		matchText: " match",
		rulesText: " rules"
	},
	edit : {
	    addCaption: "اضافه کردن رکورد",
	    editCaption: "ويرايش رکورد",
	    bSubmit: "ثبت",
	    bCancel: "انصراف",
		bClose: "بستن",
		saveData: "Data has been changed! Save changes?",
		bYes : "Yes",
		bNo : "No",
		bExit : "Cancel",
	    msg: {
	        required:"فيلدها بايد ختما پر شوند",
	        number:"لطفا عدد وعتبر وارد کنيد",
	        minValue:"مقدار وارد شده بايد بزرگتر يا مساوي با",
	        maxValue:"مقدار وارد شده بايد کوچکتر يا مساوي",
	        email: "پست الکترونيک وارد شده معتبر نيست",
	        integer: "لطفا يک عدد صحيح وارد کنيد",
			date: "لطفا يک تاريخ معتبر وارد کنيد",
			url: "is not a valid URL. Prefix required ('http://' or 'https://')"
		}
	},
	view : {
	    caption: "View Record",
	    bClose: "Close"
	},
	del : {
	    caption: "حذف",
	    msg: "از حذف گزينه هاي انتخاب شده مطمئن هستيد؟",
	    bSubmit: "حذف",
	    bCancel: "ابطال"
	},
	nav : {
		edittext: " ",
	    edittitle: "ويرايش رديف هاي انتخاب شده",
		addtext:" ",
	    addtitle: "افزودن رديف جديد",
	    deltext: " ",
	    deltitle: "حذف ردبف هاي انتخاب شده",
	    searchtext: " ",
	    searchtitle: "جستجوي رديف",
	    refreshtext: "",
	    refreshtitle: "بازيابي مجدد صفحه",
	    alertcap: "اخطار",
	    alerttext: "لطفا يک رديف انتخاب کنيد",
		viewtext: "",
		viewtitle: "View selected row"
	},
	col : {
	    caption: "نمايش/عدم نمايش ستون",
	    bSubmit: "ثبت",
	    bCancel: "انصراف"	
	},
	errors : {
		errcap : "خطا",
		nourl : "هيچ آدرسي تنظيم نشده است",
		norecords: "هيچ رکوردي براي پردازش موجود نيست",
	    model : "طول نام ستون ها محالف ستون هاي مدل مي باشد!"
	},
	formatter : {
		integer : {thousandsSeparator: " ", defaultValue: '0'},
		number : {decimalSeparator:".", thousandsSeparator: " ", decimalPlaces: 2, defaultValue: '0.00'},
		currency : {decimalSeparator:".", thousandsSeparator: " ", decimalPlaces: 2, prefix: "", suffix:"", defaultValue: '0.00'},
		date : {
			dayNames:   [
				"يک", "دو", "سه", "چهار", "پنج", "جمع", "شنب",
				"يکشنبه", "دوشنبه", "سه شنبه", "چهارشنبه", "پنجشنبه", "جمعه", "شنبه"
			],
			monthNames: [
				"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
				"ژانويه", "فوريه", "مارس", "آوريل", "مه", "ژوئن", "ژوئيه", "اوت", "سپتامبر", "اکتبر", "نوامبر", "December"
			],
			AmPm : ["ب.ظ","ب.ظ","ق.ظ","ق.ظ"],
			S: function (j) {return j < 11 || j > 13 ? ['st', 'nd', 'rd', 'th'][Math.min((j - 1) % 10, 3)] : 'th'},
			srcformat: 'Y-m-d',
			newformat: 'd/m/Y',
			masks : {
	            ISO8601Long:"Y-m-d H:i:s",
	            ISO8601Short:"Y-m-d",
	            ShortDate: "n/j/Y",
	            LongDate: "l, F d, Y",
	            FullDateTime: "l, F d, Y g:i:s A",
	            MonthDay: "F d",
	            ShortTime: "g:i A",
	            LongTime: "g:i:s A",
	            SortableDateTime: "Y-m-d\\TH:i:s",
	            UniversalSortableDateTime: "Y-m-d H:i:sO",
	            YearMonth: "F, Y"
	        },
	        reformatAfterEdit : false
		},
		baseLinkUrl: '',
		showAction: 'نمايش',
	    target: '',
	    checkbox : {disabled:true},
		idName : 'id'
	}
};
})(jQuery);
