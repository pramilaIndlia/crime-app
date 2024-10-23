<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Services\GoogleMapsService;




class LocationController extends Controller
{

    protected $googleMapsService;

    public function __construct(GoogleMapsService $googleMapsService)
    {
        $this->googleMapsService = $googleMapsService;
    }

    /**
     * Get geocoding for an address.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function geocode(Request $request)
    {
        $address = $request->input('address');
        $location = $this->googleMapsService->geocodeAddress($address);

        if ($location) {
            return response()->json($location);
        }

        return response()->json(['error' => 'Unable to geocode address'], 400);
    }

    /**
     * Get the distance between two locations.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function distance(Request $request)
    {
        $origin = $request->input('origin');
        $destination = $request->input('destination');
        $distance = $this->googleMapsService->getDistance($origin, $destination);

        if ($distance) {
            return response()->json(['distance' => $distance]);
        }

        return response()->json(['error' => 'Unable to calculate distance'], 400);
    }
}
