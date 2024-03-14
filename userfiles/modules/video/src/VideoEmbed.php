<?php
namespace MicroweberPackages\Modules\Video;

/**
 * Class VideoEmbed
 * @author Bozhidar Slaveykov
 * @package Microweber Video Embeding
 * @email bobi@microweber.com
 */

class VideoEmbed
{
    public $id;
    public $url = false;
    public $width = '100%';
    public $height = '350px;';
    public $thumbnail = false;
    public $lazyLoad = false;
    public $autoplay = false;
    public $loop = false;
    public $muted = false;
    public $hideControls = false;
    public $uploadedVideoUrl = false;
    public $embedCode = false;
    public $playUploadedVideo = false;
    public $playEmbedVideo = false;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function isUrl()
    {
        return $this->url;
    }

    /**
     * @param bool $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param string $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param string $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return bool
     */
    public function isThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @param bool $thumbnail
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    /**
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @return bool
     */
    public function isLazyLoad()
    {
        return $this->lazyLoad;
    }

    /**
     * @param bool $lazyLoad
     */
    public function setLazyLoad($lazyLoad)
    {
        $this->lazyLoad = $lazyLoad;
    }

    /**
     * @return bool
     */
    public function isAutoplay()
    {
        return $this->autoplay;
    }

    /**
     * @param bool $autoplay
     */
    public function setAutoplay($autoplay)
    {
        $this->autoplay = $autoplay;
    }

    /**
     * @return bool
     */
    public function isLoop()
    {
        return $this->loop;
    }

    /**
     * @param bool $loop
     */
    public function setLoop($loop)
    {
        $this->loop = $loop;
    }

    /**
     * @return bool
     */
    public function isHideControls()
    {
        return $this->hideControls;
    }

    /**
     * @param bool $hideControls
     */
    public function setHideControls($hideControls)
    {
        $this->hideControls = $hideControls;
    }

    /**
     * @return bool
     */
    public function isMuted()
    {
        return $this->muted;
    }

    /**
     * @param bool $muted
     */
    public function setMuted($muted)
    {
        $this->muted = $muted;
    }

    /**
     * @return bool
     */
    public function isUploadedVideoUrl()
    {
        return $this->uploadedVideoUrl;
    }

    /**
     * @param bool $uploadedVideoUrl
     */
    public function setUploadedVideoUrl($uploadedVideoUrl)
    {
        $this->uploadedVideoUrl = $uploadedVideoUrl;
    }

    /**
     * @return bool
     */
    public function getUploadedVideoUrl()
    {
        return $this->uploadedVideoUrl;
    }

    /**
     * @return bool
     */
    public function isEmbedCode()
    {
        return $this->embedCode;
    }

    /**
     * @param bool $embedCode
     */
    public function setEmbedCode($embedCode)
    {
        if (stristr($embedCode, '<iframe') !== false) {
            $embedCode = preg_replace('#\<iframe(.*?)\ssrc\=\"(.*?)\"(.*?)\>#i', '<iframe$1 src="$2?wmode=transparent"$3>', $embedCode);
        }

        $this->embedCode = $embedCode;
    }

    /**
     * @return bool
     */
    public function getEmbedCode()
    {
        return $this->embedCode;
    }

    /**
     * @return bool
     */
    public function isPlayUploadedVideo()
    {
        return $this->playUploadedVideo;
    }

    /**
     * @param bool $playUploadedVideo
     */
    public function setPlayUploadedVideo($playUploadedVideo)
    {
        $this->playUploadedVideo = $playUploadedVideo;
    }

    /**
     * @return bool
     */
    public function isPlayEmbedVideo()
    {
        return $this->playEmbedVideo;
    }

    /**
     * @param bool $playEmbedVideo
     */
    public function setPlayEmbedVideo($playEmbedVideo)
    {
        $this->playEmbedVideo = $playEmbedVideo;
    }

    public function render()
    {
        $html = false;

        // This is the uploaded video
        if ($this->isPlayUploadedVideo()) {

            $html = $this->_getHtmlVideoPlayer();
            if (empty($html)) {
                $html = 'Can\'t read video from this source file.';
            }

            return $this->_getEmbedVideoWrapper($html);
        }

        // This is the embeded video from youtube, facebook etc.
        if ($this->isPlayEmbedVideo()) {

            if ($this->_isCodeAllreadyEmbeded($this->getEmbedCode())) {
                $html = $this->getEmbedCode();
            } else {
                $videoUrl = $this->getEmbedCode();
                $videoUrlHost = $this->_getUrlHost($videoUrl);
                switch ($videoUrlHost) {
                    case 'youtube.com':
                        $html = $this->_getYoutubePlayer($videoUrl);
                        $this->providerIs = 'youtube';
                        break;
                    case 'youtu.be':
                        $html = $this->_getYoutuPlayer($videoUrl);
                        $this->providerIs = 'youtube';
                        break;
                    case 'facebook.com':
                        $html = $this->_getFacebookPlayer($videoUrl);
                        $this->providerIs = 'facebook';
                        break;
                    case 'vimeo.com':
                        $html = $this->_getVimeoPlayer($videoUrl);
                        $this->providerIs = 'vimeo';
                        break;
                    case 'metacafe.com':
                        $html = $this->_getMetCafePlayer($videoUrl);
                        $this->providerIs = 'metacafe';
                        break;
                    case 'dailymotion.com':
                        $html = $this->_getDailyMotionPlayer($videoUrl);
                        $this->providerIs = 'dailymotion';
                        break;
                }
            }

            if (!$html) {
                $html = 'Can\'t read video from this source url.';
                if (in_live_edit()) {
                    $html = "<div class='video-module-default-view mw-open-module-settings h-100 d-flex align-items-center justify-content-center'><img src='" . modules_url() . "video/video_background_cover.svg' style='width: 40px; height: 40px;'/></div>";
                }
            }

            return $this->_getEmbedIframeWrapper($html);
        }
    }

    public $providerIs = '';

    public function getProvider()
    {
        return $this->providerIs;

    }

    protected function _getFacebookPlayer($url)
    {

        $urlParse = parse_url($url);

        if (!isset($urlParse['query']) or $urlParse['query'] == false) {
            return false;
        }

        $id = explode('v=', $urlParse['query']);
        parse_str($urlParse['query'], $query);

        if (isset($query['v'])) {
            return '<script>(function(d, s, id) {  var js, fjs = d.getElementsByTagName(s)[0];  if (d.getElementById(id)) return;  js = d.createElement(s); js.id = id;  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";  fjs.parentNode.insertBefore(js, fjs);}(document, \'script\', \'facebook-jssdk\'));</script><div class="fb-post" data-href="https://www.facebook.com/video.php?v=' . $query['v'] . '" data-width="' . $this->getWidth() . '" data-height="' . $this->getHeight() . '"><div class="fb-xfbml-parse-ignore"></div></div>';
        }

        return false;
    }

    protected function _getVimeoPlayer($url)
    {

        $urlParse = parse_url($url);
        $urlParse = ltrim($urlParse['path'], '/');

        //$videoUrl = $this->_getPortocol() . 'player.vimeo.com/video/' . $urlParse . '?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=bc9b6a&wmode=transparent&autoplay=' . $this->isAutoplay();
        return  '<div style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/' . $urlParse . '?title=0&byline=0&portrait=0&autoplay=' . $this->isAutoplay() . '" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe></div><script async src="https://player.vimeo.com/api/player.js"></script>';
        //return $this->_getVideoIframe($videoUrl);
    }

    protected function _getMetCafePlayer($url)
    {

        $urlParse = parse_url($url);
        $urlPath = ltrim($urlParse['path'], '/');
        $id = explode('/', $urlPath);

        if (!isset($id[1])) {
            return false;
        }

        $videoUrl = $this->_getPortocol() . 'metacafe.com/embed/' . $id[1] . '/?ap=' . $this->isAutoplay();

        return $this->_getVideoIframe($videoUrl);
    }

    protected function _getYoutuPlayer($url)
    {

        $urlParse = parse_url($url);
        $urlParse = ltrim($urlParse['path'], '/');

        $videoUrl = $this->_getPortocol() . 'youtube.com/embed/' . $urlParse . '?v=1&wmode=transparent&autoplay=' . $this->isAutoplay();
        if ($this->isAutoplay()){
            $videoUrl .= '&mute=1';
        }
        return $this->_getVideoIframe($videoUrl);
    }

    protected function _getYoutubePlayer($url)
    {
        $urlParse = parse_url($url);
        if (!isset($urlParse['query']) or $urlParse['query'] == false) {
            return false;
        }

        $id = explode('v=', $urlParse['query']);
        parse_str($urlParse['query'], $query);

        if (isset($query['v'])) {

            $videoUrl = $this->_getPortocol() . 'youtube.com/embed/' . $query['v'] . '?v=1&wmode=transparent&autoplay=' . $this->isAutoplay();

            if ($this->isAutoplay()){
                $videoUrl .= '&mute=1';
            }
            return $this->_getVideoIframe($videoUrl);
        }

        return false;
    }

    protected function _getDailyMotionPlayer($url)
    {
        $urlParse = parse_url($url);
        $urlPath = ltrim($urlParse['path'], '/');
        $id = explode('/', $urlPath);
        $id = explode('_', $id[1]);

        if (!isset($id[0])) {
            return false;
        }

        $videoUrl = $this->_getPortocol() . 'dailymotion.com/embed/video/' . $id[0] . '/?autoPlay=' . $this->isAutoplay();

        return $this->_getVideoIframe($videoUrl);
    }

    protected function _getVideoIframe($url)
    {
        $attributes = array();
        $attributes[] = 'frameborder="0"';
        $attributes[] = 'allow="autoplay"';
        $attributes[] = 'width="' . $this->getWidth() . '"';
        $attributes[] = 'height="' . $this->getHeight() . '"';
        $attributes[] = 'allowFullScreen="true"';

        if ($this->isLazyLoad()) {
            $attributes[] = 'class="js-mw-embed-iframe-' . $this->getId() . '"';
            $attributes[] = 'style="display:none;"';
            $attributes[] = 'data-src="' . $url . '"';
        } else {
            $attributes[] = 'src="' . $url . '"';
        }

        return '<iframe ' . implode(" ", $attributes) . '></iframe>';
    }

    protected function _getUrlHost($url)
    {

        $parsedUrl = parse_url($url);

        if (!isset($parsedUrl['host'])) {
            return false;
        }

        return preg_replace('/^www\./', '', $parsedUrl['host']);
    }

    protected function _getHtmlVideoPlayer()
    {
        $attributes = array();

        if (!$this->isHideControls()) {
            $attributes[] = 'controls="1"';
        }

        if ($this->isLoop()) {
            $attributes[] = 'loop="1"';
        }

        if ($this->isMuted()) {
            $attributes[] = 'muted="1"';
        }

        $attributes[] = 'width="' . $this->getWidth() . '"';
        $attributes[] = 'height="' . $this->getHeight() . '"';

        if ($this->isLazyLoad()) {
            $attributes[] = 'class="js-mw-embed-htmlvideo-' . $this->getId() . '"';
            $attributes[] = 'style="display:none;"';
            $attributes[] = 'data-src="' . $this->getUploadedVideoUrl() . '"';
        } else {
            $attributes[] = 'src="' . $this->getUploadedVideoUrl() . '"';
        }

        if ($this->isThumbnail()) {
            $attributes[] = 'poster="' . $this->getThumbnail() . '"';
        }

        if ($this->isAutoplay()) {
            $attributes[] = 'autoplay="1"';
        }


        return '<video ' . implode(" ", $attributes) . '></video>';
    }

    protected function _getEmbedWrapper($class, $html)
    {
        if ($this->isLazyLoad()) {
            $class .= ' js-mw-embed-wrapper-' . $this->getId();
        }

        return '<div class="mwembed ' . $class . '" ' . $this->_getEmbedWrapperStyles() . '>' . $html . '</div>';
    }

    protected function _getEmbedIframeWrapper($html = '')
    {
        return $this->_getEmbedWrapper('mwembed-iframe', $html);
    }

    protected function _getEmbedVideoWrapper($html = '')
    {
        return $this->_getEmbedWrapper('mwembed-video', $html);
    }

    protected function _getEmbedWrapperStyles()
    {
        $styles = array();
        $styles[] = 'width:' . $this->getWidth();
        $styles[] = 'height:' . $this->getHeight();

        if ($this->isThumbnail() && $this->isLazyLoad()) {
            $styles[] = 'background:#000';
            $styles[] = 'background: url(' . modules_url() . 'video/video_background_cover.svg' . ') center center, url(' . $this->getThumbnail() . ') center center';
            $styles[] = 'background-repeat:no-repeat';
            $styles[] = 'background-size: 300px 100px, contain';
            $styles[] = 'background-position: center center';
        }

        if (!$this->isThumbnail() && $this->isLazyLoad()) {
            $styles[] = 'background:#000';
            $styles[] = 'background-image:url(' . modules_url() . 'video/video_background_cover.svg' . ')';
            $styles[] = 'background-repeat:no-repeat';
            $styles[] = 'background-position: center center';
            $styles[] = 'background-size: 60px';
        }

        return 'style="' . implode(';', $styles) . '"';
    }

    protected function _getPortocol()
    {

        $protocol = "http://";

        if (isset($_SERVER['SERVER_PORT'])) {
            if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) {
                $protocol = "https://";
            }
        }

        return $protocol;
    }

    protected function _isCodeAllreadyEmbeded($code)
    {
        $code = strtolower($code);
        if (stristr($code, '<iframe') != false or stristr($code, '<object') != false or stristr($code, '<embed') != false) {
            return true;
        } else {
            return false;
        }
    }
}
