var calendar = {
	month_names:new Array(t("January"),t("February"),t("March"),t("April"),t("May"),t("June"),t("July"),t("August"),t("September"),t("October"),t("November"),t("December")),
	month_days:new Array(31,28,31,30,31,30,31,31,30,31,30,31),
	//Get today's date - year, month, day and date
	today : new Date(),
	opt : {},
	data:"",

	//Functions
	wrt:function(txt) {
		this.data += txt;
	},
	
	/* Copyright Robert Nyman, http://www.robertnyman.com | Free to use if this text is included */
	getStyle: function(ele, property){
		var value = "";
		if(document.defaultView && document.defaultView.getComputedStyle){
			var css = document.defaultView.getComputedStyle(ele, null);
			value = css ? css.getPropertyValue(property) : null;
		}
		else if(ele.currentStyle){
			property = property.replace(/\-(\w)/g, function (strMatch, p1){
				return p1.toUpperCase();
			});
			value = ele.currentStyle[property];
		}
		return value;
	},
	getPosition:function(ele) {
		var x = 0;
		var y = 0;
		while (ele) {
			x += ele.offsetLeft;
			y += ele.offsetTop;
			ele = ele.offsetParent;
		}
		if (navigator.userAgent.indexOf("Mac") != -1 && typeof document.body.leftMargin != "undefined") {
			x += document.body.leftMargin;
			offsetTop += document.body.topMargin;
		}
	
		var xy = new Array(x,y);
		return xy;
	},
	selectDate:function(year,month,day) {
		document.getElementById(this.opt["input"]).value = year + "-" + month + "-" + day; // Date format is :HARDCODE:
		document.getElementById(this.opt['calendar']).style.display = "none";
	},
	makeCalendar:function(year,month) {
		//Display the table
		var next_month = month+1;
		var next_month_year = year;
		if(next_month>=12) {
			next_month = 0;
			next_month_year++;
		}
		var previous_month = month-1;
		var previous_month_year = year;
		if(previous_month< 0) {
			previous_month = 11;
			previous_month_year--;
		}
		
		this.wrt("<table>");
		this.wrt("<tr><th><a href='javascript:calendar.makeCalendar("+(previous_month_year)+","+(previous_month)+");' title='"+this.month_names[previous_month]+" "+(previous_month_year)+"'>&lt;</a></th>");
		this.wrt("<th colspan='5' class='calendar-title'>"+this.month_names[month]+" "+year+"</th>");
		this.wrt("<th><a href='javascript:calendar.makeCalendar("+(next_month_year)+","+(next_month)+");' title='"+this.month_names[next_month]+" "+(next_month_year)+"'>&gt;</a></th></tr>");
		this.wrt("<tr class='header'><td>"+t("Sun")+"</td><td>"+t("Mon")+"</td><td>"+t("Tue")+"</td><td>"+t("Wed")+"</td><td>"+t("Thu")+"</td><td>"+t("Fri")+"</td><td>"+t("Sat")+"</td></tr>");
		
		//Get the first day of this month
		var first_day = new Date(year,month,1);
		var start_day = first_day.getDay();
		
		var d = 1;
		var flag = 0;
		var days_in_this_month = this.month_days[month];

		//Create the calender
		for(var i=0;i<=5;i++) {
			this.wrt("<tr>");
			for(var j=0;j<7;j++) {
				if(d > days_in_this_month) flag=0; //If the days has overshooted the number of days in this month, stop writing
				else if(j >= start_day && !flag) flag=1;//If the first day of this month has come, start the date writing

				if(flag) {
					var w = d, mon = month+1;
					if(w < 10)	w	= "0" + w;
					if(mon < 10)mon = "0" + mon;

					//Is it today?
					var is_today = '';
					var yea = this.today.getYear();
					yea = (yea > 1900) ? yea : yea+1900;

					if(yea == year && this.today.getMonth() == month && this.today.getDate() == d)
						is_today = ' today';

					this.wrt("<td class='days"+is_today+"'><a href='javascript:calendar.selectDate(\""+year+"\",\""+mon+"\",\""+w+"\")'>"+w+"</a></td>");
					d++;
				} else {
					this.wrt("<td class='days'>&nbsp;</td>");
				}
			}
			this.wrt("</tr>");
		}
		this.wrt("</table>");

		document.getElementById(this.opt['calendar']).innerHTML = this.data;
		this.data = "";
	},

	load : function() {
		var input = document.getElementById(this.opt['input']);
		if(!input) return; //If the input field is not there, exit.

		if(!document.getElementById(this.opt['calendar'])) {
			var div = document.createElement('div');
			if(!this.opt['calendar']) this.opt['calendar'] = 'calender_div_'+ Math.round(Math.random() * 100);

			div.setAttribute('id',this.opt['calendar']);

			var xy = this.getPosition(input);
			var width = parseInt(this.getStyle(input,'width'));

			div.style.left=(xy[0]+width+10)+"px";
			div.style.top=xy[1]+"px";
			div.className="calendar-box";

			//document.getElementsByTagName("body")[0].appendChild(div);
			document.getElementsByTagName("body")[0].insertBefore(div,document.getElementsByTagName("body")[0].firstChild);
		}
		this.year	= this.today.getYear();
		this.date	= this.today.getDate();
		this.month	= this.today.getMonth();
		this.day	= this.today.getDay();
		this.year = (this.year > 2000) ? this.year : this.year + 1900;

		this.makeCalendar(this.year,this.month);
		var ths=this;
		input.onclick=function() {
			document.getElementById(ths.opt['calendar']).style.display = "block";
		}
	},

	init: function(options) {
		//Leap year support
		if(this.today.getYear() % 4 == 0) this.month_days[1] = 29;

		var default_opts = {
			'input':'',
			'calendar':'',
			'format':'%Y-%m-%d' //:TODO: Not used - yet.
		}

		for(var key in options) {
			default_opts[key] = options[key];
		}
		this.opt = default_opts;

		var ths = this;
		addEvent(window,'load',function(){ths.load();});
	}
}
