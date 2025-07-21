<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\State\HomeProcessor;

#[ApiResource(
    operations: [
        new Get(
            name: 'home',
            uriTemplate: '/',
            description: 'Get home',
            processor: HomeProcessor::class
        )
    ]
)]
class Home
{}