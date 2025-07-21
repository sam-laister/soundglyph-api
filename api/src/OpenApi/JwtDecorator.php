<?php

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model;
use ApiPlatform\OpenApi\OpenApi;

final class JwtDecorator implements OpenApiFactoryInterface
{
    private $decorated;

    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);

        // Find the /auth path and set default values
        $pathItem = $openApi->getPaths()->getPath('/auth');
        if ($pathItem) {
            $post = $pathItem->getPost();
            $requestBody = $post->getRequestBody();
            $content = $requestBody->getContent();
            if (isset($content['application/json'])) {
                $schema = $content['application/json']->getSchema();
                $schema['properties']['email']['default'] = 'laister.sam@gmail.com';
                $schema['properties']['password']['default'] = 'password';
                $content['application/json'] = new Model\MediaType($schema);
                $requestBody = $requestBody->withContent($content);
                $post = $post->withRequestBody($requestBody);
                $pathItem = $pathItem->withPost($post);
                $openApi->getPaths()->addPath('/auth', $pathItem);
            }
        }

        return $openApi;
    }
}