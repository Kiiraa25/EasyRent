<?php

namespace App\Service\Api;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\String\Slugger\SluggerInterface;

class DataGouvAddressService
{

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly SluggerInterface $slugger
    ) {}

    /**
     * Obtenir les coordonnées d'une ville à partir de l'API data.gouv.fr
     *
     * @param string $city Nom de la ville
     * @return array|null Les coordonnées de la ville ['longitude', 'latitude'] ou null si non trouvées
     */
    public function getCityCoordinates(string $city,)
    {
        $sluggedCity = $this->slugger->slug($city);
        $queries =
            [
                'q' => $sluggedCity->toString(),
                'limit' => 1,
            ];
        $buildQueries = http_build_query($queries);

        $url = "https://api-adresse.data.gouv.fr/search/?$buildQueries";


        $response = $this->client->request(
            'GET',
            $url
        );
        // dd($url);


        $statusCode = $response->getStatusCode();

        if ($statusCode !== 200) {
            throw new BadRequestException("error service)");
        }
        return $response->toArray();
    }


    public function getVehicleCoordinates(string $address, int $postalCode)
    {
        $fullAddress = $address . ' ' . $postalCode;
        $sluggedAddress = $this->slugger->slug($fullAddress);
        $queries =
            [
                'q' => $sluggedAddress->toString(),
                'limit' => 1,
            ];
        $buildQueries = http_build_query($queries);

        $url = "https://api-adresse.data.gouv.fr/search/?$buildQueries";


        $response = $this->client->request(
            'GET',
            $url
        );

        $statusCode = $response->getStatusCode();

        if ($statusCode !== 200) {
            throw new BadRequestException("error service)");
        }
        return $response->toArray();
    }
}
