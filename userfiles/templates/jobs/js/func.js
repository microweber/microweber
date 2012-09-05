$(document).ready(function(){


JobsDropdown = "";

$("#category_dropdown li").each(function(){
    var val = $(this).attr("id");
    var val = val.replace("category_item_", "");
    var text = $(this).find("a").html();
    if($(this).find("a").hasClass("active")){
      JobsDropdown = JobsDropdown + "<option selected='selected' value='" + val + "'>" + text + "</option>";
    }
    else{
      JobsDropdown = JobsDropdown + "<option value='" + val + "'>" + text + "</option>";
    }

});

 JobsDropdown = "<select name='category' id='category_dropdown_rended'><option value=''>Select category</option>" + JobsDropdown + "</select>";

 $("#category_dropdown").remove();

 $("#category_area").html(JobsDropdown);


 $(".paging li").hide();



 var active_paging =  $(".paging a.active").parent();
 active_paging.show();
 active_paging.next().show();
 active_paging.next().next().show();
 active_paging.prev().show();
 active_paging.prev().prev().show();

 var next_url =   active_paging.next().find("a").attr("href");
 var prev_url =   active_paging.prev().find("a").attr("href");


 $("#next").attr("href", next_url);
 $("#prev").attr("href", prev_url);





 $(".jobsform").submit(function(){
      var keyword = $("input[name='keyword']");
    if(keyword.val()=='Keyword'){
        keyword.val("")
    }

 });


 $(".applytothejob_but a").click(function(){

    var top = $("form:last").offset().top;

    $("html, body").animate({scrollTop:top}, 500);

 return false;
 });


});