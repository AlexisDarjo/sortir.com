<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GeocodeController extends AbstractController
{
    /**
     * @Route("/reverse-geocode", name="reverse_geocode", methods={"GET"})
     */
    public function reverseGeocode(Request $request): Response
    {
        $lat = $request->query->get('lat');
        $lng = $request->query->get('lng');

        // URL de l'API Nominatim pour le géocodage inversé
        $url = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat={$lat}&lon={$lng}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);

        $response = [
            'adresse' => $data['address']['road'] ?? '',
            'codePostal' => $data['address']['postcode'] ?? '',
            'nomVille' => $data['address']['city'] ?? $data['address']['town'] ?? $data['address']['village'] ?? '',
        ];

        return $this->json($response);
    }
}
