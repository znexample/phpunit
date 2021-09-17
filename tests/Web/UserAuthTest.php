<?php

namespace Tests\Web;

use Tests\Enums\UserEnum;
use Tests\Helpers\FixtureHelper;

class UserAuthTest extends BaseWebTest
{

    protected function fixtures(): array
    {
        return FixtureHelper::getCommonFixtures();
    }

    public function testPage()
    {
        $browser = $this->getBrowser();
        $this->sendRequest($browser, 'auth');
        $this->createAssert($browser)
            ->assertContainsContent('Логин')
            ->assertContainsContent('Пароль');
    }

    public function testLoginSuccess()
    {
        $browser = $this->getBrowser();
        $this->sendForm($browser, 'auth', 'Вход', [
            'form[login]' => 'admin',
            'form[password]' => 'Wwwqqq111',
        ]);
        $this->createAssert($browser)
            ->assertContainsContent('Hello, world!');
    }

    public function testLoginBadPassword()
    {
        $browser = $this->getBrowser();
        $this->sendForm($browser, 'auth', 'Вход', [
            'form[login]' => 'admin',
            'form[password]' => 'Wwwqqq222',
        ]);
        $this->createAssert($browser)
            ->assertContainsContent('Неверный пароль');
    }

    public function testLoginAlready()
    {
        $browser = $this->getBrowserByLogin(UserEnum::ADMIN);
        $this->sendRequest($browser, 'auth');
        $this->createAssert($browser)
            ->assertContainsContent('Hello, world!');
    }
}
