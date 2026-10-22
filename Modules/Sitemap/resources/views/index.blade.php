<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<?php echo '<?xml-stylesheet type="text/xsl" href="' . site_url('sitemap.xsl') . '"?>' . "\n"; ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>{{ site_url('sitemap.xml/categories') }}</loc>
    </sitemap>
    <sitemap>
        <loc>{{ site_url('sitemap.xml/products') }}</loc>
    </sitemap>
    <sitemap>
        <loc>{{ site_url('sitemap.xml/posts') }}</loc>
    </sitemap>
    <sitemap>
        <loc>{{ site_url('sitemap.xml/tags') }}</loc>
    </sitemap>
    <sitemap>
        <loc>{{ site_url('sitemap.xml/pages') }}</loc>
    </sitemap>
</sitemapindex> 