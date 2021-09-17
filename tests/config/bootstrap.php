<?php

require __DIR__ . '/../../vendor/autoload.php';

use ZnCore\Base\Libs\App\Factories\ApplicationFactory;
use ZnCore\Base\Libs\App\Factories\KernelFactory;

$bundles = include __DIR__ . '/../../config/bundle.php';
$kernel = KernelFactory::createConsoleKernel($bundles);
ApplicationFactory::createTest($kernel);