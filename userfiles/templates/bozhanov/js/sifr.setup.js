    var philosopher = {
      src: js_url_sfiri+'philosopher.swf',
      ratios: [7, 1.32, 11, 1.31, 13, 1.24, 14, 1.25, 19, 1.23, 27, 1.2, 34, 1.19, 42, 1.18, 47, 1.17, 48, 1.18, 69, 1.17, 74, 1.16, 75, 1.17, 1.16],
      wmode:'transparent',
      forceSingleLine: true

    };

    sIFR.activate(philosopher);

    sIFR.replace(philosopher, {
        selector: '#author',
        css: [
          '.sIFR-root { text-align: center; color: #ffffff; font-size: 39px;}'
        ]
    });
	
	
/*	
	 sIFR.replace(philosopher, {
        selector: '.nav_font',
        css: [
          '.sIFR-root { text-align: left; color: #ffffff; font-size: 20px; cursor: hand }'
        ]
    });*/
	 
	  sIFR.replace(philosopher, {
        selector: '.font_top',
        css: [
          '.sIFR-root { text-align: left; color: #ffffff; font-size:24px;}'
        ]
    });
	
	
		  sIFR.replace(philosopher, {
        selector: '.font_top2',
        css: [
          '.sIFR-root { text-align: left; color: #ffffff; font-size:20px;}'
        ]
    });
	
	
    sIFR.replace(philosopher, {
        selector: '#book-desription',
        css: [
          '.sIFR-root { text-align: left; color: #ffffff; font-size: 20px;}'
        ]
    });
    sIFR.replace(philosopher, {
        selector: '.title',
        css: [
          '.sIFR-root { text-align: left; color: #721711; font-size: 35px;}'
        ]
    });
	
	
	
	
	   sIFR.replace(philosopher, {
        selector: '.titlesm',
        css: [
          '.sIFR-root { text-align: left; color: #721711; font-size:25px;}'
        ]
    });
	
	
	
	  sIFR.replace(philosopher, {
        selector: 'a.title',
        css: [
          '.sIFR-root { text-align: left; color: #721711; font-size: 35px; text-decoration: none; cursor: hand; }'
        ]
    });
	  
	  
    sIFR.replace(philosopher, {
        selector: '.img-title',
        css: [
          '.sIFR-root { text-align: center; color: #721711; font-size: 25px;}'
        ]
    });
    sIFR.replace(philosopher, {
        selector: '.quote span',
        css: [
          '.sIFR-root { text-align: left; color: #721711; font-size: 18px;}'
        ]
    });
    sIFR.replace(philosopher, {
        selector: '#order-desc',
        css: [
          '.sIFR-root { text-align: left; color: #721711; font-size: 12px;}'
        ]
    });






    //sIFR.replace(philosopher, {selector: 'h2,h3'});






