<?php

declare(strict_types=1);

namespace App\Tests;

use App\AppServiceProvider;
use App\DogeClient;
use App\MemeClient;
use GrahamCampbell\TestBenchCore\LaravelTrait;
use GrahamCampbell\TestBenchCore\ServiceProviderTrait;

/**
 * This is the service provider test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ServiceProviderTest extends AbstractTestCase
{
    use LaravelTrait, ServiceProviderTrait;

    protected function getServiceProviderClass($app)
    {
        return AppServiceProvider::class;
    }

    public function testDogeClientIsInjectable()
    {
        $this->assertIsInjectable(DogeClient::class);
    }

    public function testMemeClientIsInjectable()
    {
        $this->assertIsInjectable(MemeClient::class);
    }
}
