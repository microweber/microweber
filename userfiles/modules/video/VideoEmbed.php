<?php

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

            $html = 'Can\'t read video from this source url.';

            if ($this->_isCodeAllreadyEmbeded($this->getEmbedCode())) {
                $html = $this->getEmbedCode();
            } else {
                $videoUrlHost = $this->_getUrlHost($this->getEmbedCode());
                switch ($videoUrlHost) {
                    case 'youtube.com':
                        $html = $this->_getYoutubePlayer();
                        break;
                    case 'youtu.be':
                        $html = $this->_getYoutuPlayer();
                        break;
                    case 'facebook.com':
                        $html = $this->_getFacebookPlayer();
                        break;
                    case 'vimeo.com':
                        $html = $this->_getVimeoPlayer();
                        break;
                    case 'metacafe.com':
                        $html = $this->_getMetCafePlayer();
                        break;
                    case 'dailymotion.com':
                        $html = $this->_getDailyMotionPlayer();
                        break;
                }
            }

            return $this->_getEmbedIframeWrapper($html);
        }
    }

    protected function _getEmbedIframeWrapper($html = '')
    {
        return '<div class="mwembed mwembed-iframe" ' . $this->_getEmbedWrapperStyles() . '>' . $html . '</div>';
    }

    protected function _getEmbedVideoWrapper($html = '')
    {
        return '<div class="mwembed mwembed-video" ' . $this->_getEmbedWrapperStyles() . '>' . $html . '</div>';
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
        $attributes[] = 'src="' . $this->getUploadedVideoUrl() . '"';
        $attributes[] = 'controls="1"';
        $attributes[] = 'width="' . $this->getWidth() . '"';
        $attributes[] = 'height="' . $this->getHeight() . '"';

        if ($this->isThumbnail()) {
            $attributes[] = 'poster="' . $this->getThumbnail() . '"';
        }

        if ($this->isAutoplay()) {
            $attributes[] = 'autoplay="1"';
        }

        return '<video ' . implode(" ", $attributes) . '></video>';
    }

    protected function _getEmbedWrapperStyles()
    {
        $styles = array();
        $styles[] = 'background:#000';
        $styles[] = 'width:' . $this->getWidth();
        $styles[] = 'height:' . $this->getHeight();

        if ($this->isThumbnail() && $this->isPlayEmbedVideo()) {
            $styles[] = 'background-image:url(' . $this->getThumbnail() . ')';
            $styles[] = 'background-repeat:no-repeat';
            $styles[] = 'background-size: contain';
            $styles[] = 'background-position: top';
        }

        return 'style="' . implode(';', $styles) . '"';
    }

    protected function _getPortocol()
    {

        $protocol = "http://";

        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) {
            $secure_connection = true;
            $protocol = "https://";
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