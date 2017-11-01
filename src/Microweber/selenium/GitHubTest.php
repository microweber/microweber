<?php


use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverBrowserType;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;

class GitHubTest extends PHPUnit_Framework_TestCase {


    protected $url = 'http://github.com';
    /**
     * @var \RemoteWebDriver
     */
    protected $webDriver;

    protected function setUp() {

        $this->webDriver = RemoteWebDriver::create(
            'http://selenium:4444/wd/hub',
            array(
                WebDriverCapabilityType::BROWSER_NAME
                => WebDriverBrowserType::FIREFOX,
            )
        );
    }

    protected function tearDown() {
        if ($this->webDriver) {
            $this->webDriver->quit();
        }
    }

    public function testGitHubHome()
    {
        $this->webDriver->get($this->url);
        // checking that page title contains word 'GitHub'
        $this->assertContains('GitHub', $this->webDriver->getTitle());
    }

}