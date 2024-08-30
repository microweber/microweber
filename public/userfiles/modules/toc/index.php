<?php
$headers = get_option('headers', $params['id']);
if ($headers == false) {
    $headers = 'h1, h2, h3, h4, h5, h6';
}
$speed = get_option('speed', $params['id']);
if ($speed == false) {
    $speed = 200;
}

$module_template = get_module_option('template', $params['id']);

if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}
if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);
} else {
    $template_file = module_templates($config['module'], 'default');
}
?>

<div>
    <script>

        if(!mw._toc_hook_ready) {
            mw._toc_hook_ready = true;

            $(window).on('load scroll resize', function(){

                const stop = $(document).scrollTop();
                const results = {};
                 mw.$('a[href*="#mw-"]').each(function(){
                     var ref = $(this.getAttribute('href'));
                        if(ref.length){
                            var off = ref.offset();
                            // if(off.top + ref.outerHeight() > stop && off.top + ref.outerHeight() < stop + innerHeight){
                            if(mw.tools.inview(ref)){
                                 const center = off.top + ref.outerHeight() - (stop + innerHeight / 2);
                                 results[Math.abs(center)] = this;
                            }
                            this.classList.remove('active');
                        }

                });
                const keys = Object.keys(results);
                if(keys.length) {
                    const el = results[Math.min.apply(null, keys)];
                    if(el) {
                        el.classList.add('active');

                    }
                }

            });

        }


        $(document).ready(function () {
            var headers = '<?php print $headers; ?>';
            var speed = '<?php print $speed; ?>';
            var id = '<?php print $params['id']; ?>';
            var headerElements = document.querySelectorAll(headers);

            var nav = document.createElement('nav');
            nav.className = 'toc-' + id;
            var list = document.createElement('ul');
            nav.appendChild(list);

            for (var i = 0; i < headerElements.length; i++) {

                var currentHeaderElement = headerElements[i];
                if (currentHeaderElement.id == '') {
                    currentHeaderElement.id = mw.id();
                }

                var link = document.createElement('a');
                link.href = '#' + currentHeaderElement.id;
                link.innerHTML = headerElements[i].textContent;

                var item = document.createElement('li');
                item.appendChild(link);
                list.appendChild(item);
            }

            document.getElementById('toc-<?php echo $params['id'];?>').appendChild(nav);

        });



    </script>
    <div>
        <?php
        if (is_file($template_file)) {
            include($template_file);
        }
        ?>
    </div>
</div>

