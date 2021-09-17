<?php

namespace Tests\Web;

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Tests\Enums\UserEnum;
use Tests\Traits\CommonFixtureTrait;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnLib\Rpc\Domain\Libs\RpcAuthProvider;
use ZnLib\Rpc\Domain\Libs\RpcFixtureProvider;
use ZnLib\Rpc\Domain\Libs\RpcProvider;
use ZnLib\Web\Test\WebAssert;
use ZnTool\Test\Base\BaseRestWebTest;

abstract class BaseWebTest extends BaseRestWebTest
{

//    use CommonFixtureTrait;

    private $fixtures = [];
    private $rpcProvider;
    private $fixtureProvider;

    /*public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->rpcProvider = new RpcProvider();
//        $this->rpcProvider->setDefaultRpcMethod($this->defaultRpcMethod());
        $this->rpcProvider->setDefaultRpcMethodVersion(1);
        $this->authProvider = new RpcAuthProvider($this->rpcProvider);
        $this->fixtureProvider = new RpcFixtureProvider($this->rpcProvider);
    }

    protected function addFixtures(array $fixtures)
    {
        $this->fixtures = ArrayHelper::merge($this->fixtures, $fixtures);
    }*/

    protected function setUp(): void
    {
        $this->setBaseUrl($_ENV['WEB_URL']);
        /*$this->addFixtures($this->fixtures());
        if ($this->fixtures) {
            $this->fixtureProvider->import($this->fixtures);
        }*/
    }

    protected function sendRequest(HttpBrowser $browser, string $uri, string $method = 'GET'): Crawler
    {
        $url = $this->getBaseUrl() . '/' . $uri;
        return $browser->request($method, $url, [], [], [
            'HTTP_ENV' => 'test',
        ]);
    }

    protected function createAssert(HttpBrowser $browser): WebAssert
    {
        return new WebAssert(null, [], '', $browser);
    }

    protected function sendForm(HttpBrowser $browser, string $uri, string $buttonValue, array $formValues): Crawler
    {
        $crawler = $this->sendRequest($browser, $uri);
        $form = $crawler->selectButton($buttonValue)->form();
        foreach ($formValues as $name => $value) {
            $form[$name] = $value;
        }
        $crawler = $browser->submit($form);
        return $crawler;
    }

    protected function assertUnauthorized(string $uri, string $method = 'GET')
    {
        $browser = $this->getBrowser();
        $this->sendRequest($browser, $uri, $method);
        $this->createAssert($browser)
            ->assertUnauthorized();
    }

    protected function getBrowser(): HttpBrowser
    {
        return new HttpBrowser(HttpClient::create());
    }

    protected function getBrowserByLogin(string $login = null, string $password = UserEnum::PASSWORD): HttpBrowser
    {
        $browser = $this->getBrowser();
        if ($login == null) {
            return $browser;
        }
        $this->authByLogin($browser, $login, $password);
        return $browser;
    }

    private function authByLogin(HttpBrowser $browser, string $login = null, string $password = UserEnum::PASSWORD)
    {
        $this->sendForm($browser, 'auth', 'Вход', [
            'form[login]' => $login,
            'form[password]' => $password,
        ]);
    }
}
