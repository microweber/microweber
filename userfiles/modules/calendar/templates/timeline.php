<?php

/*

type: layout

name: Timeline

description: Calendar Timeline

*/

?>



<style>

@media only screen and (min-width: 1170px) {
    .cd-is-hidden {
        visibility: hidden;
    }
}

header {
    height: 200px;
    line-height: 200px;
    text-align: center;
    background: #303e49;
}

header h1 {
    color: white;
    font-size: 1.8rem;
}

@media only screen and (min-width: 1170px) {
    header {
        height: 300px;
        line-height: 300px;
    }
    header h1 {
        font-size: 2.4rem;
    }
}

.cd-timeline {
    overflow: hidden;
    margin: 2em auto;
}

.cd-timeline__container {
    position: relative;
    width: 90%;
    max-width: 1170px;
    margin: 0 auto;
    padding: 2em 0;
}

.cd-timeline__container:before {
    content: '';
    position: absolute;
    top: 0;
    left: 18px;
    height: 100%;
    width: 4px;
    background: #d7e4ed;
}

@media only screen and (min-width: 1170px) {
    .cd-timeline {
        margin-top: 3em;
        margin-bottom: 3em;
    }
    .cd-timeline__container:before {
        left: 50%;
        margin-left: -2px;
    }
}

.cd-timeline__block {
    position: relative;
    margin: 2em 0;
}

.cd-timeline__block:after {
    /* clearfix */
    content: "";
    display: table;
    clear: both;
}

.cd-timeline__block:first-child {
    margin-top: 0;
}

.cd-timeline__block:last-child {
    margin-bottom: 0;
}

@media only screen and (min-width: 1170px) {
    .cd-timeline__block {
        margin: 4em 0;
    }
}

.cd-timeline__img {
    position: absolute;
    top: 0;
    left: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    -webkit-box-shadow: 0 0 0 4px white, inset 0 2px 0 rgba(0, 0, 0, 0.08), 0 3px 0 4px rgba(0, 0, 0, 0.05);
    box-shadow: 0 0 0 4px white, inset 0 2px 0 rgba(0, 0, 0, 0.08), 0 3px 0 4px rgba(0, 0, 0, 0.05);
    color: white;

    text-align: center;
        font-size: 20px;
        line-height: 36px;


}

.cd-timeline__img img {
    display: block;
    width: 24px;
    height: 24px;
    position: relative;
    left: 50%;
    top: 50%;
    margin-left: -12px;
    margin-top: -12px;
}

.cd-timeline__img.cd-timeline__img--picture {
    background: #75ce66;
}

.cd-timeline__img.cd-timeline__img--movie {
    background: #c03b44;
}

.cd-timeline__img.cd-timeline__img--location {
    background: #f0ca45;
}

@media only screen and (min-width: 1170px) {
    .cd-timeline__img {
        width: 60px;
        height: 60px;
        left: 50%;
        margin-left: -30px;
        font-size: 27px;
        line-height: 36px;
        line-height: 55px;
    }
    .cd-timeline__img.cd-timeline__img--bounce-in {
        visibility: visible;

    }
}




.cd-timeline__content {
    position: relative;
    margin-left: 60px;
    background: #f5f5f5;
    border-radius: 0.25em;
    padding: 1em;
    box-shadow: 0 3px 0 #d7e4ed;
}

.cd-timeline__content:after {
    /* clearfix */
    content: "";
    display: table;
    clear: both;
}

.cd-timeline__content:before {
    /* triangle next to content block */
    content: '';
    position: absolute;
    top: 16px;
    right: 100%;
    height: 0;
    width: 0;
    border: 7px solid transparent;
    border-right: 7px solid white;
}

.cd-timeline__content h2 {
    color: #303e49;
}

.cd-timeline__content p,
.cd-timeline__read-more,
.cd-timeline__date {
    font-size: 1.3rem;
}

.cd-timeline__content p {
    margin: 1em 0;
    line-height: 1.6;
}

.cd-timeline__read-more,
.cd-timeline__date {
    display: inline-block;
}

.cd-timeline__read-more {
    float: right;
    padding: .8em 1em;
    background: #acb7c0;
    color: white;
    border-radius: 0.25em;
}

.cd-timeline__read-more:hover {
    background-color: #bac4cb;
}

.cd-timeline__date {
    float: left;
    opacity: .7;
}

@media only screen and (min-width: 768px) {
    .cd-timeline__content h2 {
        font-size: 2rem;
    }
    .cd-timeline__content p {
        font-size: 1.6rem;
    }
    .cd-timeline__read-more,
    .cd-timeline__date {
        font-size: 1.4rem;
    }
}

@media only screen and (min-width: 1170px) {
    .cd-timeline__content {
        margin-left: 0;
        padding: 1.6em;
        width: 45%;
    }
    .cd-timeline__content:before {
        top: 24px;
        left: 100%;
        border-color: transparent;
        border-left-color: #f5f5f5;
    }
    .cd-timeline__read-more {
        float: left;
    }
    .cd-timeline__date {
        position: absolute;
        width: 100%;
        left: 122%;
        top: 6px;
        font-size: 1.6rem;
    }
    .cd-timeline__block:nth-child(even) .cd-timeline__content {
        float: right;
    }
    .cd-timeline__block:nth-child(even) .cd-timeline__content:before {
        top: 24px;
        left: auto;
        right: 100%;
        border-color: transparent;
        border-right-color: #f5f5f5;
    }
    .cd-timeline__block:nth-child(even) .cd-timeline__read-more {
        float: right;
    }
    .cd-timeline__block:nth-child(even) .cd-timeline__date {
        left: auto;
        right: 122%;
        text-align: right;
    }

}



</style>

<script>


var callendarTimeline = {
    getData: function(callback){
        var date = $(".calendar").fullCalendar('getDate');
        var y = date.year();
        var m = ("0" + (date.month() + 1)).slice(-2);
        var yearmonth = y+'-'+m;
    	return $.ajax({
            url: '<?php print api_url('calendar_get_events_api'); ?>',
            type: 'POST',
            data: 'yearmonth=' + yearmonth,
            async: false,
            success: function(data){
            	if(typeof callback == 'function'){
            	    callback.call(data, data);
            	}
            }
        });
    }
}





	$(document).ready(function() {


        /*callendarTimeline.getData(function(data){

        });   */





	});

</script>

<?php


    $data = calendar_get_events_api('2018-06');

    $count = 0;
    $step = 0;

 ?>










  <section class="cd-timeline js-cd-timeline">
        <div class="cd-timeline__container">
            <?php foreach($data as $event){

            if(!!$event['content_id']){
                $event_post = get_content_by_id($event['content_id']);
            }

                $count++;
                $step++;
                if($step == 6){
                    $step = 1;
                }

            ?>
            <div class="cd-timeline__block js-cd-block">
                <div class="cd-timeline__img cd-timeline__img--picture">

                    <span class="mw-icon-web-calendar"></span>
                </div>

                <div class="cd-timeline__content js-cd-content">
                    <h2><?php print $event['title']; ?></h2>
                    <img src="<?php print pixum(350,100); ?>" alt="">
                    <p><?php print $event['description']; ?></p>
                    <?php if(!!$event['content_id']){  ?>
                        <a href="<?php print $event_post['full_url'] ?>" class="cd-timeline__read-more">Read more</a>
                    <?php } ?>
                    <span class="cd-timeline__date">
                        <?php print date('M, d h:i', strtotime($event['start'])); ?><br><?php print date('M, d h:i', strtotime($event['end'])); ?>
                    </span>
                </div>
            </div>

                <?php } ?>


        </div>
    </section>











