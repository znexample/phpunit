<?php

namespace Tests\Rpc\User;

use Tests\Enums\UserEnum;
use ZnLib\Rpc\Domain\Enums\RpcErrorCodeEnum;
use Tests\Rpc\BaseTest;

class UserAuthTest extends BaseTest
{

    protected function fixtures(): array
    {
        return [
            'summary_attempt',
            'notify_type',
            'notify_type_i18n',
            'notify_type_transport',
            'notify_transport',
        ];
    }

    protected function defaultRpcMethod(): ?string
    {
        return 'authentication.getTokenByPassword';
    }

    // проверка на приход токена
    public function testGetTokenSuccess()
    {
        $request = $this->createRequest();
        $request->setParams([
            'login' => UserEnum::ADMIN,
            'password' => UserEnum::PASSWORD,
        ]);

        $response = $this->sendRequestByEntity($request);
        $result = $response->getResult();
        $token = $result['token'];

        $this->assertContains('bearer', $token);
    }

    public function testFailAttempt()
    {
        $request = $this->createRequest();
        $request->setParams([
            'login' => UserEnum::ADMIN,
            'password' => 'qwerty123#$%',
        ]);

        for ($i = 0; $i < 2; $i++) {
            $response = $this->sendRequestByEntity($request);
            $this->getRpcAssert($response)->assertErrorCode(RpcErrorCodeEnum::SERVER_ERROR_INVALID_PARAMS);
        }

        $response = $this->sendRequestByEntity($request);
        $this->getRpcAssert($response)
            ->assertErrorCode(RpcErrorCodeEnum::APPLICATION_ERROR)
            ->assertErrorMessage('Попытки исчерпаны. Действие заблокировано.');

        $response = $this->sendRequestByEntity($request);
        $this->getRpcAssert($response)
            ->assertErrorCode(RpcErrorCodeEnum::APPLICATION_ERROR)
            ->assertErrorMessage('Попытки исчерпаны. Повторите позже.');
    }

    public function testSuccessAttempt()
    {
        $request = $this->createRequest();
        $request->setParams([
            'login' => UserEnum::ADMIN,
            'password' => UserEnum::PASSWORD,
        ]);

        for ($i = 0; $i < 5; $i++) {
            $response = $this->sendRequestByEntity($request);
            $result = $response->getResult();
            $token = $result['token'];
            $this->assertContains('bearer', $token);
        }
    }

    public function testGetTokenBadPassword()
    {
        $request = $this->createRequest();
        $request->setParamItem('login', UserEnum::ADMIN);
        $request->setParamItem('password', 'badPassword');
        $response = $this->sendRequestByEntity($request);

        $expected = [
            [
                "field" => "password",
                "message" => "Неверный пароль",
            ],
        ];

        $this->getRpcAssert($response)->assertUnprocessableEntityErrors($expected);
    }

    public function testGetTokenNotFoundLogin()
    {
        $request = $this->createRequest();
        $request->setParamItem('login', 'qwerty123456');
        $request->setParamItem('password', 'badPassword');
        $response = $this->sendRequestByEntity($request);

        $expected = [
            [
                "field" => "login",
                "message" => "Пользователь не существует",
            ],
        ];

        $this->getRpcAssert($response)->assertUnprocessableEntityErrors($expected);
    }

    public function testGetTokenEmptyPassword()
    {
        $request = $this->createRequest();
        $request->setParamItem('login', UserEnum::ADMIN);
        $response = $this->sendRequestByEntity($request);

        $expected = [
            [
                "field" => "password",
                "message" => "Значение не должно быть пустым.",
            ],
        ];

        $this->getRpcAssert($response)->assertUnprocessableEntityErrors($expected);
    }
}
