<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ClimatiqService
{
    public function __construct(private HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getApi()
    {
        $number = 88;

        return $number;

    }

    public function getEmissionEstimate(int $conso, string $region = 'FR', string $activity = 'electricity-supply_grid-source_residual_mix')
    {
        $url = 'https://api.climatiq.io/data/v1/estimate';

        // Préparez les données de la requête
        $data = [
            'emission_factor' => [
                'activity_id' => $activity,
                'data_version' => '^6',
                'region' => $region
            ],
            'parameters' => [
                'energy' => $conso,
                'energy_unit' => 'kWh'
            ]
        ];

        // Effectuez la requête
        $response = $this->client->request('POST', $url, [
            'headers' => [
                'Authorization' => 'Bearer V94NRCHM955KNARCRWAKBEMMMR',
                'Content-Type' => 'application/json'
            ],
            'json' => $data
        ]);

        // Décoder le JSON
        $json = $response->getContent();
        $data = json_decode($json, true);

        // Récupérer seulement le CO2 total
        $co2_total = $data['constituent_gases']['co2e_total'];
        return $co2_total;
    }
}