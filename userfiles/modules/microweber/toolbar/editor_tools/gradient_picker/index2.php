<?php must_have_access(); ?>

<script src="https://cdn.jsdelivr.net/gh/artf/grapick@master/dist/grapick.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/artf/grapick@master/dist/grapick.min.css">

<style>
    #gp {
        height: 500px;
    }
</style>

<style>



    .brand {
        user-select: none;
        margin: -12rem 0 3rem;
        text-align: center;
        font-size: 9rem;
        font-weight: 100;
        color: white;
        font-family: 'Romanesco', cursive;
        /*text-shadow: 2px 0 0 #555, -2px 0 0 #555, 0 2px 0 #555, 0 -2px 0 #555, 1px 1px #555, -1px -1px 0 #555, 1px -1px 0 #555, -1px 1px 0 #555;*/
    }

    .grapick-cont {
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        border-radius: 3px;
        margin: -15px;
        padding: 25px 15px;
        width: 100%;
        min-height: 100px;
        background: white;
    }

    .grp-preview {
        border-radius: 3px;
    }

    .grp-wrapper {
        height: 40px !important;
    }

    .inputs {
        margin: 25px 0px 15px;
    }

    .form-control {
        background-color: transparent;
        border: 1px solid #ccc;
        border-radius: 3px;
        height: 30px;
        width: 49%;
    }
    .copy-grid {
        display: flex;
    }
    .txt-value {
        flex-grow: 1;
        background-color: #f1f1f1;
        padding: 5px 7px;
        color: #555;
        min-height: 30px;
    }
    .copy-btn {
        margin-left: 5px;
        background-color: #4491a1;
        color: white;
        cursor: pointer;
        padding: 10px 15px;
    }
    .txt-value, .copy-btn {
        display: block;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 3px;
    }
    .copy-btn svg {
        height: 20px;
        fill: currentColor;
    }
</style>

<div class="container">
    <h1 class="brand">Grapick</h1>
    <div class="grapick-cont">
        <div id="grapick"></div>
        <div class="inputs">
            <select class="form-control" id="switch-type">
                <option value="">- Select Type -</option>
                <option value="radial">Radial</option>
                <option value="linear">Linear</option>
                <option value="repeating-radial">Repeating Radial</option>
                <option value="repeating-linear">Repeating Linear</option>
            </select>

            <select class="form-control" id="switch-angle">
                <option value="">- Select Direction -</option>
                <option value="top">Top</option>
                <option value="right">Right</option>
                <option value="center">Center</option>
                <option value="bottom">Bottom</option>
                <option value="left">Left</option>
            </select>
        </div>
        <div class="copy-grid" style="display: none">
            <textarea class="txt-value" readonly></textarea>
            <button title="Copy" class="copy-btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 21H8V7h11m0-2H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2m-3-4H4c-1.1 0-2 .9-2 2v14h2V3h12V1z"></path></svg>
            </button>
        </div>
    </div>
</div>

<script type="text/javascript">
    var upType, unAngle, gp;
    var copyTxt = document.querySelector('.txt-value');
    var swType = document.getElementById('switch-type');
    var swAngle = document.getElementById('switch-angle');
    var copyToClipboard = function(str) {
        var el = document.createElement('textarea');
        el.value = str;
        el.setAttribute('readonly', '');
        el.style.position = 'absolute';
        el.style.left = '-9999px';
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
    };
    swType.addEventListener('change', function(e) {
        gp && gp.setType(this.value || 'linear');
    });

    swAngle.addEventListener('change', function(e) {
        gp && gp.setDirection(this.value || 'right');
    });

    var copyBtn = document.querySelector('.copy-btn');
    copyBtn.addEventListener('click', function(e) {
        var iconOrig = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 21H8V7h11m0-2H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2m-3-4H4c-1.1 0-2 .9-2 2v14h2V3h12V1z"></path></svg>';
        var iconDone = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21 7L9 19l-5.5-5.5 1.41-1.41L9 16.17 19.59 5.59 21 7z"></path></svg>';
        copyToClipboard(copyTxt.value);
        copyBtn.innerHTML = iconDone;
        setTimeout(() => copyBtn.innerHTML = iconOrig, 2000)
    });

    var createGrapick = function() {
        gp = new Grapick({
            el: '#grapick',
            direction: 'right',
            min: 1,
            max: 99,
        });


        gp.addHandler(1, '#085078', 1);
        gp.addHandler(99, '#85D8CE', 1, { keepSelect: 1 });


        gp.on('change', function(complete) {
            const value = gp.getValue();
            document.body.style.backgroundImage = value;
            copyTxt.value = value;

            //

            var selected = mw.top().liveEditSelector.selected;
            if(selected && typeof selected[0] != 'undefined'){

                selected[0].style.backgroundImage = value
                mw.top().wysiwyg.change(selected[0]);

            }



        })
        gp.emit('change');
    };

    var destroyGrapick = function() {
        gp.destroy();
        gp = 0;
    }

    createGrapick();
    // createGrapick(); destroyGrapick();
</script>
