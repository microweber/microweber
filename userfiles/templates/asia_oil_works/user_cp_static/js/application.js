$('document').ready(function() {
  
  /**
   * Use the jQuery DataTables-plugin to add
   * interactive features to your tables.
   */
   
  $(".datatable").dataTable();
  
  /**
   * Use search in real-time 
   **/
  
  $("#search").liveSearch({url: '/admincp/search?q='}); // Edit this url to match your search action
  // Nice animation on focus
  $("#search").focus(function() { $(this).animate({ width: '175px' }) });
  $("#search").blur(function() {
    if($(this).val() == "") { // Only go back to normal when nothing's filled in
      $(this).animate({ width: '100px' })
    }
  });
  
  /**
   * Tags in input fields
   */
  $('.tags').tagsInput();
  
  /**
   * Placeholders in forms
   */
   
  $('input[type="text"]').placeholderFunction('input-focused');
  
  /**
   * Skin select boxes, checkboxes and radiobuttons
   */
   
  $('select').select_skin();
  $('input[type=checkbox], input[type=radio]').prettyCheckboxes();
  
  /**
   * Functional secondary menu using tabs
   */
  
  $(".tab").hide();
  
  if($("nav#secondary ul li.current").length < 1) {
    $("nav#secondary ul li:first-child").addClass("current");    
  }
  
  var link = $("nav#secondary ul li.current a").attr("href");
  $(link).show();
  
  $("nav#secondary ul li a").click(function() {
    if(!$(this).hasClass("current")) {
      $("nav#secondary ul li").removeClass("current");
      $(this).parent().addClass("current");
      $(".tab").hide();
      var link = $(this).attr("href");
      $(link).show();
      initBackground();
    }
    return false;
  });
  
  /**
   * Validate your forms
   */
   
  $("form").validate();
  
  /**
   * Gallery on hover
   */
   
  $(".gallery img").wrap("<div class=\"image\">");
  $(".gallery .image").append('<div class="overlay"></div><a href="#" class="button icon search">View</a>');
  $(".gallery .image").hover(function() {
    $(this).find("a").stop().animate({ opacity: 1}, 200);
    $(this).find(".overlay").stop().animate({ opacity: .5}, 200);
  }, function() {
    $(this).find("a").stop().animate({ opacity: 0}, 200);
    $(this).find(".overlay").stop().animate({ opacity: 0}, 200);
  });
  
  /**
   * Wysiwym-editor
   */
   
  $('.wysiwym').wymeditor({
    logoHtml: '',
    toolsItems: [
      {'name': 'Bold', 'title': 'Strong', 'css': 'wym_tools_strong'}, 
      {'name': 'Italic', 'title': 'Emphasis', 'css': 'wym_tools_emphasis'},
      {'name': 'InsertOrderedList', 'title': 'Ordered_List',
        'css': 'wym_tools_ordered_list'},
      {'name': 'InsertUnorderedList', 'title': 'Unordered_List',
        'css': 'wym_tools_unordered_list'},
      {'name': 'Indent', 'title': 'Indent', 'css': 'wym_tools_indent'},
      {'name': 'Outdent', 'title': 'Outdent', 'css': 'wym_tools_outdent'},
      {'name': 'CreateLink', 'title': 'Link', 'css': 'wym_tools_link'},
      {'name': 'Paste', 'title': 'Paste_From_Word', 'css': 'wym_tools_paste'},
      {'name': 'Undo', 'title': 'Undo', 'css': 'wym_tools_undo'},
      {'name': 'Redo', 'title': 'Redo', 'css': 'wym_tools_redo'}
    ],
    containersItems: [
      {'name': 'P', 'title': 'Paragraph', 'css': 'wym_containers_p'},
      {'name': 'H4', 'title': 'Heading_4', 'css': 'wym_containers_h4'}
    ]
  });
  
  /** 
   * Dynamically create charts from tables
   * Just add class="linechart" and replace
   * 'line' with any type of chart.
   */
   
  // This array contains the colors that will be used in charts
  var colors = ['#005ba8','#1175c9',
                '#92d5ea','#ee8310',
                '#8d10ee','#5a3b16',
                '#26a4ed','#f45a90',
                '#e9e744'];
                
  $('.barchart').visualize({ type: 'bar', colors: colors });
  $('.linechart').visualize({ type: 'line', lineWeight: 2, colors: colors });
  $('.areachart').visualize({ type: 'area', lineWeight: 1, colors: colors });
  $('.piechart').visualize({ type: 'pie', colors: colors });
  $('.barchart, .linechart, .areachart, .piechart').hide();
  
  /**
   * Make sure the background gradient reaches
   * the bottom of the page.
   */
  function initBackground() {
    if($('#container').height() < window.innerHeight) {
      $('#container').height(window.innerHeight);
    }
  }
  
  initBackground();
  
});