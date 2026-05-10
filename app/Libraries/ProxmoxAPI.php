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
        // Hardcode langsung tanpa memanggil file .env
        $this->hostname = '103.160.213.137';
        $this->port = 8006;
        $this->tokenId = 'root@pam!vlabci4';
        $this->tokenSecret = 'dfc2601e-506a-40ae-a96d-701bbf1bb700';
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
            // Timeout dinaikkan sedikit agar saat clone OS tidak gagal/terputus di tengah jalan
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 30
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