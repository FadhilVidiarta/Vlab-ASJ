<?php

namespace App\Libraries;

class ProxmoxAPI
{
    private string $hostname;
    private int $port;
    private string $tokenId;
    private string $tokenSecret;

    public function __construct()
    {
        // Mengambil data kredensial dengan aman dari file .env
        $this->hostname = env('PROXMOX_HOST', '127.0.0.1');
        $this->port = (int) env('PROXMOX_PORT', 8006);
        $this->tokenId = env('PROXMOX_TOKEN_ID', '');
        $this->tokenSecret = env('PROXMOX_TOKEN_SECRET', '');
    }

    /**
     * Mengirim request ke API Proxmox
     * @param string $endpoint URL endpoint (contoh: /cluster/nextid)
     * @param string $method Method request HTTP (GET, POST, PUT, DELETE)
     * @param array  $data Array data untuk payload (POST/PUT)
     * @return array|mixed
     */
    public function request(string $endpoint, string $method = 'GET', array $data = [])
    {
        $url = "https://{$this->hostname}:{$this->port}/api2/json" . $endpoint;

        $curl = curl_init();

        $headers = [
            'Authorization: PVEAPIToken=' . $this->tokenId . '=' . $this->tokenSecret,
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded'
        ];

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CONNECTTIMEOUT => 2,
            CURLOPT_TIMEOUT => 3
        ];

        if ($method === 'POST' || $method === 'PUT') {
            $options[CURLOPT_POSTFIELDS] = http_build_query($data);
        }

        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);
        $err = curl_error($curl);

        if ($err) {
            return ['error' => true, 'message' => 'CURL Error: ' . $err];
        }

        return json_decode((string) $response, true);
    }
}