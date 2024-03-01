<?php

/*

  type: layout

  name: Default

  description: Default skin


*/

?>

<style>
    .wrapper {
        max-width: 100%;
        overflow: hidden;
    }

    .marquee {
        white-space: nowrap;
        overflow: hidden;
        display: inline-block;
        animation: marquee 10s linear infinite;
    }

    .marquee div {
        display: inline-block;
    }

    @keyframes marquee {
        0% {
            transform: translate(0, 0);
        }

        100% {
            transform: translate(-50%, 0);
        }
    }

</style>

<div class="wrapper">
    <div class="marquee">
            Hello world!
    </div>
</div>
