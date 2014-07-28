<?php

namespace raynet;


use Zend\Http\Client;
use Zend\Json\Json;

class RaynetCrmRestClient {

    const RAYNETCRM_URL = 'https://hell/api/v2/%s';

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
     * Requests RAYNET Cloud CRM REST API. Check https://s3-eu-west-1.amazonaws.com/static-raynet/webroot/api-doc.html for any further details.
     *
     * @param $serviceName string URL service name
     * @param $method string Http method
     * @param $request array request
     * @return \Zend\Http\Response response
     */
    private function callRaynetcrmRestApi($serviceName, $method, $request) {
        $client = new Client('', array(
            'adapter' => 'Zend\Http\Client\Adapter\Curl',
            'curloptions' => array(
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false
            )
        ));
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

    /**
     * A helper method for REST API URL building
     *
     * @param $serviceName string Service to be requested
     * @return string Specific URL for API call
     */
    private function buildUrl($serviceName) {
        return sprintf(self::RAYNETCRM_URL, $serviceName);
    }

    /**
     * Finds personId by email
     *
     * @param $email
     * @return int personId
     */
    public function findPerson($email) {
        return $this->findRecordId('person', array(
            'contactInfo.email' => '"' . $email . '"'
        ));
    }

    /**
     * Finds primary address id of a company by its id.
     *
     * @param $companyId
     * @return int primaryAddressId
     */
    public function findCompanyPrimaryAddressId($companyId) {
        $response = $this->callRaynetcrmRestApi('company/' . $companyId, 'GET', array());

        if ($response->getStatusCode() === self::HTTP_CODE_OK) {
            return Json::decode($response->getBody())->data->addresses[0]->address->id;
        } else {
            return null;
        }
    }

    /**
     * Finds companyId by by its name
     *
     * @param $name
     * @return int companyId
     */
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

    /**
     * An internal method utilizing RAYNET Cloud CRM REST API for new record creation.
     *
     * @param $entityName string a name of entity
     * @param array $data request data
     * @return int id of newly created record
     * @throws RaynetGenericException when an error occurs
     */
    private function createRecord($entityName, array $data) {
        $response = $this->callRaynetcrmRestApi($entityName, self::HTTP_METHOD_PUT, $data);

        if ($response->getStatusCode() === self::HTTP_CODE_CREATED) {
            return Json::decode($response->getBody())->data->id;
        } else {
            throw new RaynetGenericException();
        }
    }

    /**
     * An internal method utilizing RAYNET Cloud CRM REST API for retrieving entity id by search criteria
     *
     * @param $entityName
     * @param array $criteria
     * @return int id of found record
     */
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