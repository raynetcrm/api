<?php

/**
 * Class Raynetcrm
 *
 * @version 1.0
 * @author RAYNET s.r.o. <info@raynet.cz>
 */
class Raynetcrm {

    const RAYNETCRM_URL = 'https://raynet.cz/%s/api/v1/service/%s';

    const SUCCESS_KEY = 'success';
    const HTTP_OK = 200;

    private $fInstanceName;
    private $fUserName;
    private $fApiKey;

    /**
     * All records created through this API are owned by user specified through constructor.
     *
     * @param $instanceName instance name -> can be found in URL e.g. https://raynet.cz/myinstance -> myinstance
     * @param $userName
     * @param $apiKey generated token from application (Users` profile -> Change security -> Reset new API key)
     */
    public function __construct($instanceName, $userName, $apiKey) {
        $this->fInstanceName = $instanceName;
        $this->fUserName = $userName;
        $this->fApiKey = $apiKey;
    }

    /**
     * Inserts lead into RAYNET CRM via curl.
     *
     * @param array $data data describing lead
     * @param array $notifyUserList list of users who receive notifications
     * @param string $notifyUserMessage message to be shown in notification
     * @return bool true on success
     */
    public function insertLead(array $data, array $notifyUserList = array(), $notifyUserMessage = '') {
        $data['notifyUserList'] = $notifyUserList;
        $data['notifyMessage'] = $notifyUserMessage;

        $response = $this->sendPost($this->buildUrl('insertLead'), $data);
        if ($response !== false && $this->isSuccessResponse($response)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Sends http base authed post request via curl, no exception is thrown
     *
     * @param $url
     * @param array $data post data to be sent
     * @param int $expectedCode expected HTTP code to be returned
     * @return mixed false on fail or data
     */
    private function sendPost($url, array $data, $expectedCode = self::HTTP_OK) {
        try {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($curl, CURLOPT_USERPWD, $this->buildAuthInfo());
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=UTF-8'
            ));

            $response = curl_exec($curl);
            $info = curl_getinfo($curl);
            curl_close($curl);

            if ($response === false || $info['http_code'] !== $expectedCode) {
                return false;
            } else {
                return json_decode($response, true);
            }
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * RAYNET CRM response should return json containing success property with value of true,
     * any other response stands for error.
     * @param array $response response from CRM request
     * @return bool
     */
    private function isSuccessResponse(array $response) {
        return array_key_exists(self::SUCCESS_KEY, $response) && $response[self::SUCCESS_KEY] === true;
    }

    /**
     * Builds URL for desired service, method is responsible for correct URL prefixing having instance name in mind.
     * @param $serviceName
     * @return string
     */
    private function buildUrl($serviceName) {
        return sprintf(self::RAYNETCRM_URL, $this->fInstanceName, $serviceName);
    }

    /**
     * @return string base auth formatted username:apitoken string
     */
    private function buildAuthInfo() {
        return sprintf('%s:%s', $this->fUserName, $this->fApiKey);
    }
}
