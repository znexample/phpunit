<?php

namespace Tests\Traits;

use Tests\Libs\DynamicFileRepository;
use ZnCore\Base\Libs\App\Helpers\ContainerHelper;

trait RepositoryTestTrait
{

    abstract protected function itemsFileName(): string;

    protected function getRepository(): DynamicFileRepository
    {
        /** @var DynamicFileRepository $repository */
        $repository = ContainerHelper::getContainer()->get(DynamicFileRepository::class);
        $repository->setFileName($this->itemsFileName());
        return $repository;
    }
}
