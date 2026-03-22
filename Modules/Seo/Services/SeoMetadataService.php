<?php

namespace Modules\Seo\Services;

use Modules\Content\Models\Content;
use MicroweberPackages\Option\Facades\Option;

class SeoMetadataService
{
    /**
     * Default changefreq values for sitemap
     */
    const CHANGE_FREQ_ALWAYS = 'always';
    const CHANGE_FREQ_HOURLY = 'hourly';
    const CHANGE_FREQ_DAILY = 'daily';
    const CHANGE_FREQ_WEEKLY = 'weekly';
    const CHANGE_FREQ_MONTHLY = 'monthly';
    const CHANGE_FREQ_YEARLY = 'yearly';
    const CHANGE_FREQ_NEVER = 'never';

    /**
     * Default Open Graph types
     */
    const OG_TYPE_WEBSITE = 'website';
    const OG_TYPE_ARTICLE = 'article';
    const OG_TYPE_PRODUCT = 'product';

    /**
     * Default Twitter card types
     */
    const TWITTER_CARD_SUMMARY = 'summary';
    const TWITTER_CARD_SUMMARY_LARGE = 'summary_large_image';
    const TWITTER_CARD_APP = 'app';
    const TWITTER_CARD_PLAYER = 'player';

    /**
     * Get SEO metadata for a content item
     *
     * @param Content|null $content
     * @return array
     */
    public function getMetadata(?Content $content = null): array
    {
        if (!$content) {
            return $this->getDefaultMetadata();
        }

        return [
            'title' => $this->getTitle($content),
            'description' => $this->getDescription($content),
            'keywords' => $this->getKeywords($content),
            'canonical_url' => $this->getCanonicalUrl($content),
            'robots' => $this->getRobotsMeta($content),
            'og' => $this->getOpenGraphData($content),
            'twitter' => $this->getTwitterCardData($content),
        ];
    }

    /**
     * Get meta title for content
     *
     * @param Content|null $content
     * @return string
     */
    public function getTitle(?Content $content = null): string
    {
        if ($content) {
            $title = $content->content_meta_title ?? $content->title;
            if (!empty($title)) {
                return $this->sanitizeMetaText($title);
            }
        }

        return $this->getSiteTitle();
    }

    /**
     * Get meta description for content
     *
     * @param Content|null $content
     * @return string
     */
    public function getDescription(?Content $content = null): string
    {
        if ($content) {
            $description = $content->content_meta_description ?? $content->description;
            if (!empty($description)) {
                return $this->sanitizeMetaText($description, 160);
            }
        }

        return $this->getSiteDescription();
    }

    /**
     * Get meta keywords for content
     *
     * @param Content|null $content
     * @return string
     */
    public function getKeywords(?Content $content = null): string
    {
        if ($content && !empty($content->content_meta_keywords)) {
            return $this->sanitizeMetaText($content->content_meta_keywords);
        }

        return $this->getSiteKeywords();
    }

    /**
     * Get canonical URL for content
     *
     * @param Content|null $content
     * @return string
     */
    public function getCanonicalUrl(?Content $content = null): string
    {
        if ($content) {
            if (!empty($content->canonical_url)) {
                return $content->canonical_url;
            }

            return $content->link();
        }

        return site_url();
    }

    /**
     * Get robots meta directive
     *
     * @param Content|null $content
     * @return string
     */
    public function getRobotsMeta(?Content $content = null): string
    {
        // Check if content is active first (highest priority)
        if ($content && (!$content->is_active || $content->is_deleted)) {
            return 'noindex, nofollow';
        }

        // Then check for custom robots meta
        if ($content && !empty($content->robots_meta)) {
            return $content->robots_meta;
        }

        return Option::getValue('website_robots_meta', 'index, follow') ?? 'index, follow';
    }

    /**
     * Get Open Graph data for content
     *
     * @param Content|null $content
     * @return array
     */
    public function getOpenGraphData(?Content $content = null): array
    {
        if (!$content) {
            return $this->getDefaultOpenGraphData();
        }

        $data = [
            'title' => $content->og_title ?? $content->content_meta_title ?? $content->title ?? $this->getSiteTitle(),
            'description' => $content->og_description ?? $content->content_meta_description ?? $content->description ?? $this->getSiteDescription(),
            'type' => $content->og_type ?? $this->getOpenGraphType($content),
            'url' => $this->getCanonicalUrl($content),
            'site_name' => $this->getSiteTitle(),
            'locale' => $this->getLocale(),
        ];

        // Add image if available
        $imageUrl = $this->getOpenGraphImage($content);
        if ($imageUrl) {
            $data['image'] = $imageUrl;
        }

        return $data;
    }

    /**
     * Get Twitter Card data for content
     *
     * @param Content|null $content
     * @return array
     */
    public function getTwitterCardData(?Content $content = null): array
    {
        if (!$content) {
            return $this->getDefaultTwitterCardData();
        }

        $data = [
            'card' => $content->twitter_card ?? self::TWITTER_CARD_SUMMARY_LARGE,
            'title' => $content->twitter_title ?? $content->og_title ?? $content->content_meta_title ?? $content->title ?? $this->getSiteTitle(),
            'description' => $content->twitter_description ?? $content->og_description ?? $content->content_meta_description ?? $content->description ?? $this->getSiteDescription(),
        ];

        // Add image if available
        $imageUrl = $this->getTwitterImage($content);
        if ($imageUrl) {
            $data['image'] = $imageUrl;
        }

        // Add site handle if configured
        $siteHandle = Option::getValue('website_twitter_site');
        if ($siteHandle) {
            $data['site'] = '@' . ltrim($siteHandle, '@');
        }

        return $data;
    }

    /**
     * Get sitemap data for content
     *
     * @param Content $content
     * @return array
     */
    public function getSitemapData(Content $content): array
    {
        if ($content->exclude_from_sitemap || !$content->is_active || $content->is_deleted) {
            return [];
        }

        return [
            'loc' => $content->link(),
            'lastmod' => $content->updated_at ? $content->updated_at->format('Y-m-d') : now()->format('Y-m-d'),
            'changefreq' => $content->sitemap_changefreq ?? $this->getDefaultChangefreq($content),
            'priority' => (float) ($content->sitemap_priority ?? $this->getDefaultPriority($content)),
        ];
    }

    /**
     * Get default metadata for the site
     *
     * @return array
     */
    public function getDefaultMetadata(): array
    {
        return [
            'title' => $this->getSiteTitle(),
            'description' => $this->getSiteDescription(),
            'keywords' => $this->getSiteKeywords(),
            'canonical_url' => site_url(),
            'robots' => Option::getValue('website_robots_meta', 'index, follow'),
            'og' => $this->getDefaultOpenGraphData(),
            'twitter' => $this->getDefaultTwitterCardData(),
        ];
    }

    /**
     * Get site title
     *
     * @return string
     */
    public function getSiteTitle(): string
    {
        return Option::getValue('website_title', 'My Site') ?? 'My Site';
    }

    /**
     * Get site description
     *
     * @return string
     */
    public function getSiteDescription(): string
    {
        return Option::getValue('website_description', '') ?? '';
    }

    /**
     * Get site keywords
     *
     * @return string
     */
    public function getSiteKeywords(): string
    {
        return Option::getValue('website_keywords', '') ?? '';
    }

    /**
     * Get default Open Graph data
     *
     * @return array
     */
    protected function getDefaultOpenGraphData(): array
    {
        $data = [
            'title' => $this->getSiteTitle(),
            'description' => $this->getSiteDescription(),
            'type' => self::OG_TYPE_WEBSITE,
            'url' => site_url(),
            'site_name' => $this->getSiteTitle(),
            'locale' => $this->getLocale(),
        ];

        // Add default image if configured
        $defaultImage = Option::getValue('website_og_image');
        if ($defaultImage) {
            $data['image'] = $defaultImage;
        }

        return $data;
    }

    /**
     * Get default Twitter Card data
     *
     * @return array
     */
    protected function getDefaultTwitterCardData(): array
    {
        $data = [
            'card' => self::TWITTER_CARD_SUMMARY_LARGE,
            'title' => $this->getSiteTitle(),
            'description' => $this->getSiteDescription(),
        ];

        // Add default image if configured
        $defaultImage = Option::getValue('website_twitter_image');
        if ($defaultImage) {
            $data['image'] = $defaultImage;
        }

        // Add site handle if configured
        $siteHandle = Option::getValue('website_twitter_site');
        if ($siteHandle) {
            $data['site'] = '@' . ltrim($siteHandle, '@');
        }

        return $data;
    }

    /**
     * Get Open Graph type based on content type
     *
     * @param Content $content
     * @return string
     */
    protected function getOpenGraphType(Content $content): string
    {
        switch ($content->content_type) {
            case 'product':
                return self::OG_TYPE_PRODUCT;
            case 'post':
                return self::OG_TYPE_ARTICLE;
            default:
                return self::OG_TYPE_WEBSITE;
        }
    }

    /**
     * Get Open Graph image for content
     *
     * @param Content $content
     * @return string|null
     */
    protected function getOpenGraphImage(Content $content): ?string
    {
        // First check for specific OG image
        if (!empty($content->og_image)) {
            return $content->og_image;
        }

        // Then try to get content's featured image
        if (method_exists($content, 'thumbnail') && $content->thumbnail()) {
            return $content->thumbnail();
        }

        // Return default site image
        return Option::getValue('website_og_image');
    }

    /**
     * Get Twitter image for content
     *
     * @param Content $content
     * @return string|null
     */
    protected function getTwitterImage(Content $content): ?string
    {
        // First check for specific Twitter image
        if (!empty($content->twitter_image)) {
            return $content->twitter_image;
        }

        // Fall back to OG image
        return $this->getOpenGraphImage($content);
    }

    /**
     * Get default changefreq based on content type
     *
     * @param Content $content
     * @return string
     */
    protected function getDefaultChangefreq(Content $content): string
    {
        switch ($content->content_type) {
            case 'post':
                return self::CHANGE_FREQ_WEEKLY;
            case 'product':
                return self::CHANGE_FREQ_WEEKLY;
            case 'page':
                return $content->is_home ? self::CHANGE_FREQ_DAILY : self::CHANGE_FREQ_MONTHLY;
            default:
                return self::CHANGE_FREQ_WEEKLY;
        }
    }

    /**
     * Get default priority based on content type
     *
     * @param Content $content
     * @return float
     */
    protected function getDefaultPriority(Content $content): float
    {
        if ($content->is_home) {
            return 1.0;
        }

        switch ($content->content_type) {
            case 'page':
                return 0.8;
            case 'product':
                return 0.7;
            case 'post':
                return 0.6;
            default:
                return 0.5;
        }
    }

    /**
     * Get current locale
     *
     * @return string
     */
    protected function getLocale(): string
    {
        return app()->getLocale();
    }

    /**
     * Sanitize meta text for output
     *
     * @param string $text
     * @param int|null $maxLength
     * @return string
     */
    protected function sanitizeMetaText(string $text, ?int $maxLength = null): string
    {
        // Strip HTML tags
        $text = strip_tags($text);

        // Decode HTML entities
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Normalize whitespace
        $text = preg_replace('/\s+/', ' ', $text);

        // Trim
        $text = trim($text);

        // Truncate if needed
        if ($maxLength && mb_strlen($text) > $maxLength) {
            $text = mb_substr($text, 0, $maxLength - 3) . '...';
        }

        return $text;
    }

    /**
     * Render all meta tags as HTML
     *
     * @param Content|null $content
     * @return string
     */
    public function renderMetaTags(?Content $content = null): string
    {
        $html = [];
        
        // Title
        $html[] = $this->renderTitle($content);
        
        // Description
        $description = $this->getDescription($content);
        if (!empty($description)) {
            $html[] = '<meta name="description" content="' . $this->escapeAttribute($description) . '">';
        }
        
        // Keywords
        $keywords = $this->getKeywords($content);
        if (!empty($keywords)) {
            $html[] = '<meta name="keywords" content="' . $this->escapeAttribute($keywords) . '">';
        }
        
        // Robots
        $robots = $this->getRobotsMeta($content);
        if (!empty($robots)) {
            $html[] = '<meta name="robots" content="' . $this->escapeAttribute($robots) . '">';
        }
        
        // Canonical URL
        $canonicalUrl = $this->getCanonicalUrl($content);
        if (!empty($canonicalUrl)) {
            $html[] = '<link rel="canonical" href="' . $this->escapeAttribute($canonicalUrl) . '">';
        }
        
        // Open Graph
        $html[] = $this->renderOpenGraph($content);
        
        // Twitter Card
        $html[] = $this->renderTwitterCard($content);
        
        return implode("\n", array_filter($html));
    }

    /**
     * Render title tag
     *
     * @param Content|null $content
     * @return string
     */
    public function renderTitle(?Content $content = null): string
    {
        $title = $this->getTitle($content);
        return '<title>' . $this->escapeHtml($title) . '</title>';
    }

    /**
     * Render description meta tag
     *
     * @param Content|null $content
     * @return string
     */
    public function renderDescription(?Content $content = null): string
    {
        $description = $this->getDescription($content);
        if (empty($description)) {
            return '';
        }
        return '<meta name="description" content="' . $this->escapeAttribute($description) . '">';
    }

    /**
     * Render Open Graph meta tags
     *
     * @param Content|null $content
     * @return string
     */
    public function renderOpenGraph(?Content $content = null): string
    {
        $ogData = $this->getOpenGraphData($content);
        $html = [];
        
        foreach ($ogData as $key => $value) {
            if (empty($value)) {
                continue;
            }
            
            if ($key === 'image') {
                $html[] = '<meta property="og:image" content="' . $this->escapeAttribute($value) . '">';
            } elseif ($key === 'locale') {
                $html[] = '<meta property="og:locale" content="' . $this->escapeAttribute($value) . '">';
            } elseif ($key === 'site_name') {
                $html[] = '<meta property="og:site_name" content="' . $this->escapeAttribute($value) . '">';
            } else {
                $html[] = '<meta property="og:' . $key . '" content="' . $this->escapeAttribute($value) . '">';
            }
        }
        
        return implode("\n", $html);
    }

    /**
     * Render Twitter Card meta tags
     *
     * @param Content|null $content
     * @return string
     */
    public function renderTwitterCard(?Content $content = null): string
    {
        $twitterData = $this->getTwitterCardData($content);
        $html = [];
        
        foreach ($twitterData as $key => $value) {
            if (empty($value)) {
                continue;
            }
            
            $html[] = '<meta name="twitter:' . $key . '" content="' . $this->escapeAttribute($value) . '">';
        }
        
        return implode("\n", $html);
    }

    /**
     * Escape HTML entities
     *
     * @param string $text
     * @return string
     */
    protected function escapeHtml(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Escape attribute value
     *
     * @param string $text
     * @return string
     */
    protected function escapeAttribute(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
