<?php must_have_access(); ?>


<style>

    .random-color {
        width: 350px;
        box-shadow: 0 0 20px rgba(0, 139, 253, 0.25);
        margin: 50px auto;
        padding: 20px;
        height: 400px;
    }

    .display {
        width: 350px;
        height: 200px;
        border: 2px solid white;
        box-shadow: 0 0 20px rgba(1, 1, 1, 0.35);
        background: linear-gradient(to right, #FFAAAA, #734C8F)
    }

    .codess {
        padding: 5px;
        margin-top: 30px;
        margin-bottom: 30px;
        font-family: sans-serif;
        letter-spacing: 1px;
        box-shadow: 0 0 20px rgba(0, 139, 253, 0.25);
    }

    .color1, .color2 {
        width: 70px;
        height: 40px;
        float: left;
        margin: 10px;
        margin-top: 20px;
    }

    select {
        float: right;
        margin-top: 25px;
        width: 130px;
        height: 35px;
    }
</style>

<div class="random-color">

    <div class="display" id="gradient"></div>
    <div class="codess"></div>

    <input type="color" class="color1" name="color1" value="#FFAAAA">
    <input type="color" class="color2" name="color2" value="#734C8F">
    <select name="toDirection">
        <option value="to right">to right</option>
        <option value="to right bottom">to right bottom</option>
        <option value="to right top">to right top</option>
        <option value="to left">to left</option>
        <option value="to left bottom">to left bottom</option>
        <option value="to left top">to left top</option>
        <option value="to bottom">to bottom</option>
        <option value="to top">to top</option>
    </select>


</div>
<script type="text/javascript">


    //Some classes and html functions need to determine a constant
    var css = document.querySelector(".codess") // color code
    var color1 = document.querySelector(".color1") // 1st color
    var color2 = document.querySelector(".color2") // 2nd color
    var bodys = document.getElementById("gradient") // color display
    var linearDirection = document.getElementsByName("toDirection")[0]  //Select box

    //displays default CSS RGBA values for linear-gradient

    function currentSettings() {
        var CSSprop = window.getComputedStyle(bodys, null).getPropertyValue("background-image")
        // console.log(CSSprop)
        css.textContent = CSSprop
    }

    currentSettings()

    //You have to make arrangements to see the color code in the display

    function returnColor() {
        var newstyle =
            "linear-gradient("+ linearDirection.value+ ", "+ color1.value+ ","+ color2.value+ ")"

        bodys.style.background =newstyle;

        var selected = mw.top().liveEditSelector.selected;
        if(selected && typeof selected[0] != 'undefined'){

            selected[0].style.backgroundImage = newstyle
            mw.top().wysiwyg.change(selected[0]);

        }

        currentSettings()

    }
    $( document ).ready(function() {


        document.querySelector('select[name="toDirection"]').onchange = returnColor;
        color1.addEventListener("input", returnColor)
        color2.addEventListener("input", returnColor)



    });



</script>









