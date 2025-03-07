<?php

declare(strict_types=1);

namespace App\Controller\Api\Admin\GeoLite;

use App\Container\SettingsAwareTrait;
use App\Controller\SingleActionInterface;
use App\Entity\Api\Admin\GeoLiteStatus;
use App\Http\Response;
use App\Http\ServerRequest;
use App\OpenApi;
use App\Service\IpGeolocator\GeoLite;
use App\Sync\Task\UpdateGeoLiteTask;
use App\Utilities\Types;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;

#[OA\Post(
    path: '/admin/geolite',
    operationId: 'postGeoLite',
    description: 'Set the GeoLite MaxMindDB Database license key.',
    requestBody: new OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'geolite_license_key',
                    type: 'string',
                    nullable: true,
                ),
            ]
        )
    ),
    tags: [OpenApi::TAG_ADMIN],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Success',
            content: new OA\JsonContent(
                ref: GeoLiteStatus::class
            )
        ),
        new OA\Response(ref: OpenApi::REF_RESPONSE_ACCESS_DENIED, response: 403),
        new OA\Response(ref: OpenApi::REF_RESPONSE_GENERIC_ERROR, response: 500),
    ]
)]
final class PostAction implements SingleActionInterface
{
    use SettingsAwareTrait;

    public function __construct(
        private readonly UpdateGeoLiteTask $geoLiteTask
    ) {
    }

    public function __invoke(
        ServerRequest $request,
        Response $response,
        array $params
    ): ResponseInterface {
        $newKey = trim(
            Types::string($request->getParsedBodyParam('geolite_license_key'))
        );

        $settings = $this->readSettings();
        $settings->setGeoliteLicenseKey($newKey);
        $this->writeSettings($settings);

        if (!empty($newKey)) {
            $this->geoLiteTask->updateDatabase($newKey);
            $version = GeoLite::getVersion();
        } else {
            @unlink(GeoLite::getDatabasePath());
            $version = null;
        }

        return $response->withJson(
            new GeoLiteStatus(
                $version,
                $newKey
            )
        );
    }
}
