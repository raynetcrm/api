<?php

namespace raynet;


use Zend\Http\Client;
use Zend\Json\Json;

class RaynetCrmRestClient {

    const RAYNETCRM_URL = 'https://raynet.cz/%s/api/v1/service/%s';
    const HTTP_METHOD_GET = 'GET';
    const HTTP_METHOD_PUT = 'PUT';
    const HTTP_CODE_OK = 200;
    const HTTP_CODE_CREATED = 201;

    private $fInstanceName;
    private $fUserName;
    private $fApiKey;

    public function __construct($instanceName, $userName, $apiKey) {
        $this->fInstanceName = $instanceName;
        $this->fUserName = $userName;
        $this->fApiKey = $apiKey;
    }

    /**
     * @param $serviceName string URL service name
     * @param $method string Http method
     * @param $request array request
     * @return \Zend\Http\Response response
     */
    private function callRaynetcrmRestApi($serviceName, $method, $request) {
        $client = new Client();
        $client->setMethod($method);
        $client->setUri($this->buildUrl($serviceName));
        $client->setHeaders(array(
            'X-Instance-Name' => $this->fInstanceName,
            'Content-Type' => 'application/json; charset=UTF-8'
        ));
        $client->setAuth($this->fUserName, $this->fApiKey);

        if ($method === self::HTTP_METHOD_GET) {
            $client->setParameterGet($request);
        } else {
            $client->setRawBody(Json::encode($request));
        }

        return $client->send();
    }

    private function buildUrl($serviceName) {
        return sprintf(self::RAYNETCRM_URL, $serviceName);
    }

    public function findPerson($email) {
        return $this->findRecordId('person', array(
            'contactInfo.email' => '"' . $email . '"'
        ));
    }

    public function findCompanyPrimaryAddressId($companyId) {
        $response = $this->callRaynetcrmRestApi('company/' . $companyId, 'GET', array());

        if ($response->getStatusCode() === self::HTTP_CODE_OK) {
            return Json::decode($response->getBody())->data->addresses[0]->address->id;
        } else {
            return null;
        }
    }

    public function findCompanyId($name) {
        return $this->findRecordId('company', array(
            'name' => '"' . $name  . '"'
        ));
    }

    public function createPerson($personData) {
        return $this->createRecord('person', $personData);
    }

    public function createCompany($companyData) {
        return $this->createRecord('company', $companyData);
    }

    public function createActivity($activityType, $activityData) {
        $this->createRecord($activityType, $activityData);
    }

    private function createRecord($entityName, array $data) {
        $response = $this->callRaynetcrmRestApi($entityName, self::HTTP_METHOD_PUT, $data);

        if ($response->getStatusCode() === self::HTTP_CODE_CREATED) {
            return Json::decode($response->getBody())->data->id;
        } else {
            throw new RaynetGenericException();
        }
    }

    private function findRecordId($entityName, array $criteria) {
        $response = $this->callRaynetcrmRestApi($entityName, self::HTTP_METHOD_GET, $criteria);

        if ($response->getStatusCode() === self::HTTP_CODE_OK) {
            $body = Json::decode($response->getBody());

            if ($body->success && $body->totalCount > 0) {
                return $body->data[0]->id;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}