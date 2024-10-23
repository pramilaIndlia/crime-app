<?php

namespace App\Services;

use GuzzleHttp\Client;

class GoogleMapsService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Geocode an address to get latitude and longitude.
     *
     * @param string $address
     * @return array
     */
    public function geocodeAddress($address)
    {
        $apiKey = env('AIzaSyB8icl26mqovWAoDAXB9wOv6Y81JkJxtKg');
        $response = $this->client->get('https://maps.googleapis.com/maps/api/geocode/json', [
            'query' => [
                'address' => $address,
                'key' => $apiKey,
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        if ($data['status'] === 'OK') {
            return $data['results'][0]['geometry']['location']; // Return lat/lng
        }

        return null; // Handle errors
    }

    /**
     * Calculate distance between two locations using Distance Matrix API.
     *
     * @param string $origin
     * @param string $destination
     * @return array
     */
    public function getDistance($origin, $destination)
    {
        $apiKey = env('AIzaSyB8icl26mqovWAoDAXB9wOv6Y81JkJxtKg');
        $response = $this->client->get('https://maps.googleapis.com/maps/api/distancematrix/json', [
            'query' => [
                'origins' => $origin,
                'destinations' => $destination,
                'key' => $apiKey,
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        if ($data['status'] === 'OK') {
            return $data['rows'][0]['elements'][0]['distance']['text']; // Return distance
        }

        return null; // Handle errors
    }
}
