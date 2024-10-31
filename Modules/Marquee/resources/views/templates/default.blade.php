
<style>
    #v-marquee-<?php echo $id; ?>{
        overflow: hidden;
        font-size: <?php echo $fontSize; ?>px;
        line-height: normal;
        color: <?php echo $textColor; ?>;
        font-weight: <?php echo $textWeight; ?>;
        font-style: <?php echo $textStyle; ?>;


    }
    @media only screen and (max-width: 1399px) {
        #v-marquee-<?php echo $id; ?> {
            font-size: max(15px, <?php echo $fontSize * 0.8 ?>px);

        }
    }
    @media only screen and (max-width: 1199px) {
        #v-marquee-<?php echo $id; ?> {
            font-size: max(15px, <?php echo $fontSize * 0.65 ?>px);

        }
    }
    @media only screen and (max-width: 991px) {
        #v-marquee-<?php echo $id; ?> {
            font-size: max(15px, <?php echo $fontSize * 0.55 ?>px);

        }
    }
    @media only screen and (max-width: 767px) {
        #v-marquee-<?php echo $id; ?> {
            font-size: max(15px, <?php echo $fontSize * 0.45 ?>px);

        }
    }
    @media only screen and (max-width: 575px) {
        #v-marquee-<?php echo $id; ?> {
            font-size: max(15px, <?php echo $fontSize * 0.4 ?>px);

        }
    }
    @media only screen and (max-width: 479px) {
        #v-marquee-<?php echo $id; ?> {
            font-size: max(15px, <?php echo $fontSize * 0.3 ?>px);

        }
    }
    @media only screen and (max-width: 375px) {
        #v-marquee-<?php echo $id; ?> {
            font-size: max(15px, <?php echo $fontSize * 0.2 ?>px);

        }
    }
</style>

<div id="v-marquee-{{ $id }}">
    {{ $text }}
</div>

<script type="module">
    import marquee from '{{ asset('modules/marquee/js/vanilla-marquee.min.js') }}';
    new marquee(document.getElementById('v-marquee-{{ $id }}'), {
        speed: '{{ $animationSpeed }}',
    });
</script>
