<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    // update to fix issue from scrutinizer

    /**
     * @var array<string, string[]>
     */
    protected $instanceof = [];

    /**
     * @var string
     */
    protected $name = 'AppKernel';
}
