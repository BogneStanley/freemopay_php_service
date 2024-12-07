<?php

namespace App\Services;


/**
 * Class FreemoPayApiService
 *
 * This class handles interactions with the FreemoPay API, allowing for payment processing and status checks.
 *
 * @package App\Services
 */
class FreemoPayService
{
    protected ?string $accessToken = null;
    protected ?string $baseUrl = null;
    protected ?string $user = null;
    protected ?string $password = null;

    /**
     * Initialize the FreemoPayApi service.
     *
     * @param string $user                      The FreemoPay API user.
     * @param string $password                  The FreemoPay API password.
     * @param string $url                       The FreemoPay API URL.
     * @param string $accessToken (optional)    Generated access token by yourself (if it is null, a new one will be generated for each request).
     */
    public function init(string $user, string $password, string $url, ?string $accessToken = null)
    {
        if (empty($user) || empty($password) || empty($url)) {
            throw new \InvalidArgumentException("The user, password and url parameters are required for the FreemoPayApi service.");
        }

        $this->user = $user;
        $this->password = $password;
        $this->baseUrl = rtrim($url, '/');
        $this->accessToken = $accessToken;
    }


    /**
     * Generate access token if it is expired.
     */
    public function generateAccessToken(): string
    {
        if (!is_null($this->accessToken)) {
            return $this->accessToken;
        }

        try {

            $response = $this->curl($this->baseUrl . '/app/token', [
                CURLOPT_POSTFIELDS => "user=" . $this->user . "&password=" . $this->password
            ]);

            $data = $this->parseJson($response);

            $this->accessToken = $data['token'] ?? null;

            if (empty($this->accessToken) || !is_string($this->accessToken)) {
                throw new \Exception("Error generating access token: " . $data['error']);
            }

            return $this->accessToken;
        } catch (\Exception $e) {
            throw new \Exception("Error generating access token: " . $e->getMessage());
        }
    }

    /**
     * Make a payment via the API.
     *
     * @param string $payer         The payer's mobile number.
     * @param string $external_id   The external identifier of the payment.
     * @param int    $amount        The payment amount.
     * @param string $description   Payment description.
     *
     * @return array Decoded API response (array format).
     * @throws \Exception If the payment fails or there is a connection error.
     */
    public function pay(string $payer, string $external_id, int $amount, string $description = "Your payment has been processed")
    {
        try {
            $this->generateAccessToken();

            $response = $this->curl($this->baseUrl . '/payment', [
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $this->accessToken
                ],
                CURLOPT_POSTFIELDS => "payer=$payer&external_id=$external_id&amount=$amount&description=$description"
            ]);

            return $this->parseJson($response);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors du paiement : " . $e->getMessage());
        }
    }

    /**
     * Check payment status.
     *
     * @param string $reference The payment reference.
     *
     * @return string
     */
    public function checkPaymentStatus(string $reference)
    {
        try {
            $this->generateAccessToken();

            $response = $this->curl($this->baseUrl . '/payment/' . $reference, [
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $this->accessToken
                ]
            ], 'GET');

            return $this->parseJson($response);
        } catch (\Exception $e) {
            throw new \Exception("Error checking payment status: " . $e->getMessage());
        }
    }

    /**
     * Make a curl request.
     *
     * @param string $url
     * @param array $options
     * @param string $method (default: POST)
     *
     * @return string
     */
    private function curl(string $url, array $options, string $method = 'POST')
    {
        $curl = curl_init();
        curl_setopt_array($curl, $options + [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            curl_close($curl);
            throw new \Exception("CURL Error: $error");
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode >= 400) {
            throw new \Exception("HTTP Error $httpCode: $response");
        }

        return $response;
    }

    /**
     * Decode JSON response.
     *
     * @param string $response
     *
     * @return array
     */
    private function parseJson(string $response): array
    {
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Erreur lors du décodage JSON : " . json_last_error_msg());
        }
        return $data;
    }
}
