<?php

declare(strict_types=1);

namespace App\Presenter;

use Doomy\Restopus\Presenter\AbstractRestPresenter;
use Doomy\Restopus\Request\Attribute\HttpMethod;
use Doomy\Restopus\Request\Attribute\Route;
use Doomy\Restopus\Request\Enum\HttpRequestMethod;
use Nette\Application\Response;
use Nette\Application\Responses\JsonResponse;

final class HealthPresenter extends AbstractRestPresenter
{
    #[HttpMethod(HttpRequestMethod::GET)]
    #[Route('/api/health')]
    public function actionCheck(array $requestData): Response
    {
        return new JsonResponse(['status' => 'ok']);
    }
}
