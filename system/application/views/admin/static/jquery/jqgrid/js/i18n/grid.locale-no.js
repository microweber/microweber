;(function($){
/**
 * jqGrid Norwegian Translation
 * KEM
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
**/
$.jgrid = {};

$.jgrid.defaults = {
	recordtext: "Rad(er)",
	loadtext: "Laster...",
	pgtext : "/"
};
$.jgrid.search = {
    caption: "Søk...",
    Find: "Finn",
    Reset: "Nullstill",
    odata : ['lik', 'forskjellig fra', 'mindre enn', 'mindre eller lik','større enn',' større eller lik', 'starter med','slutter med','inneholder' ]
};
$.jgrid.edit = {
    addCaption: "Ny rad",
    editCaption: "Rediger",
    bSubmit: "Send",
    bCancel: "Avbryt",
	bClose: "Lukk",
    processData: "Laster...",
    msg: {
        required:"Felt er obligatorisk",
        number:"Legg inn et gyldig tall",
        minValue:"verdi må være større enn eller lik",
        maxValue:"verdi må være mindre enn eller lik",
        email: "er ikke en gyldig e-post adresse",
        integer: "Legg inn et gyldig heltall",
		date: "Legg inn en gyldig dato"
    }
};
$.jgrid.view = {
    caption: "Åpne post",
    bClose: "Lukk"
}
$.jgrid.del = {
    caption: "Slett",
    msg: "Slett valgte rad(er)?",
    bSubmit: "Slett",
    bCancel: "Avbryt",
    processData: "Behandler..."
};
$.jgrid.nav = {
	edittext: " ",
    edittitle: "Rediger valgte rad",
	addtext:" ",
    addtitle: "Legg til ny rad",
    deltext: " ",
    deltitle: "Slett valgte rader",
    searchtext: " ",
    searchtitle: "Søk",
    refreshtext: "",
    refreshtitle: "Oppdater tabell",
    alertcap: "Advarsel",
    alerttext: "Velg rad",
	viewtext: " ",
	viewtitle: "Åpne valgt rad"
};
// setcolumns module
$.jgrid.col ={
    caption: "Vis/skjul kolonner",
    bSubmit: "Utfør",
    bCancel: "Avbryt"
};
$.jgrid.errors = {
	errcap : "Feil",
	nourl : "Ingen url er satt",
	norecords: "Ingen poster å behandle",
    model : "colNames og colModel er forskjellig lengde!"
};
$.jgrid.formatter = {
	integer : {thousandsSeparator: " ", defaulValue: 0},
	number : {decimalSeparator:",", thousandsSeparator: " ", decimalPlaces: 2, defaulValue: 0},
	currency : {decimalSeparator:",", thousandsSeparator: " ", decimalPlaces: 2, prefix: "", suffix:"", defaulValue: 0},
	date : {
		dayNames:   [
			"sø.", "ma.", "ti.", "on.", "to.", "fr.", "lø.",
			"Søndag", "Mandag", "Tirsdag", "Onsdag", "Torsdag", "Fredag", "Lørdag"
		],
		monthNames: [
			"jan.", "feb.", "mars", "april", "mai", "juni", "juli", "aug.", "sep.", "okt.", "nov.", "des.",
			"januar", "februar", "mars", "april", "mai", "juni", "juli", "august", "september", "oktober", "november", "desember"
		],
		AmPm : ["","","",""],
		S: function (j) {return '.'},
		srcformat: 'Y-m-d H:i:s',
		newformat: 'Y-m-d H:i:s',
		masks: {
            ISO8601Long:"Y-m-d H:i:s",
            ISO8601Short:"Y-m-d",
            ShortDate: "j.n.Y",
            LongDate: "l j. F Y",
            FullDateTime: "l j. F Y kl. G.i.s",
            MonthDay: "j. F",
            ShortTime: "H:i",
            LongTime: "H:i:s",
            SortableDateTime: "Y-m-d\\TH:i:s",
            UniversalSortableDateTime: "Y-m-d H:i:sO",
            YearMonth: "F Y"
        },
        reformatAfterEdit : false
	},
	baseLinkUrl: '',
	showAction: 'show',
	addParam: '',
	checkbox: { disabled: true }
};
// NO
})(jQuery);
