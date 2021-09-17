<?php

namespace Tests\Traits;

use ZnLib\Rpc\Domain\Entities\RpcRequestEntity;
use ZnLib\Rpc\Domain\Entities\RpcResponseEntity;
use ZnLib\Rpc\Test\RpcAssert;

trait CrudRpcTestTrait
{

    use RepositoryTestTrait;

    abstract protected function baseMethod(): string;

    abstract protected function createRequest(string $login = null): RpcRequestEntity;

    abstract protected function getRpcAssert(RpcResponseEntity $response = null): RpcAssert;

    abstract protected function getExistedId(): int;

    protected function getNextId(): int
    {
        return $this->getRepository()->count() + 1;
    }

    protected function getFirstId(): int
    {
        return 1;
    }

    protected function getTotalCount(): int
    {
        return $this->getRepository()->count();
    }

    protected function all(array $data = [], string $login = null): RpcResponseEntity
    {
        $request = $this->createRequest($login);
        $request->setMethod($this->baseMethod() . '.all');
        $request->setParams($data);
        return $this->sendRequestByEntity($request);
    }

    protected function create(array $data, string $login = null): RpcResponseEntity
    {
        $request = $this->createRequest($login);
        $request->setMethod($this->baseMethod() . '.create');
        $request->setParams($data);
        return $this->sendRequestByEntity($request);
    }

    protected function update(array $data, string $login = null): RpcResponseEntity
    {
        $request = $this->createRequest($login);
        $request->setMethod($this->baseMethod() . '.update');
        $request->setParams($data);
        return $this->sendRequestByEntity($request);
    }

    protected function deleteById(int $id, string $login = null): RpcResponseEntity
    {
        $request = $this->createRequest($login);
        $request->setMethod($this->baseMethod() . '.delete');
        $request->setParamItem('id', $id);
        return $this->sendRequestByEntity($request);
    }

    protected function oneById(int $id, string $login = null): RpcResponseEntity
    {
        $request = $this->createRequest($login);
        $request->setMethod($this->baseMethod() . '.oneById');
        $request->setParamItem('id', $id);
        return $this->sendRequestByEntity($request);
    }

    protected function assertExistsById(int $id, string $login = null)
    {
        $response = $this->oneById($id, $login);
        $this->getRpcAssert($response)->assertResult($this->getRepository()->oneByIdAsArray($id));
    }

    protected function assertNotFoundById(int $id, string $login = null)
    {
        $response = $this->oneById($id, $login);
        $this->getRpcAssert($response)->assertNotFound();
    }

    protected function assertItem(array $data, string $login = null)
    {
        $response = $this->oneById($data['id'], $login);
        $this->getRpcAssert($response)->assertResult($data);
    }
}
