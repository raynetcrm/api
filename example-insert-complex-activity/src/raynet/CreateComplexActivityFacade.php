<?

namespace raynet;


use Zend\Http\Client;

class CreateComplexActivityFacade {

    private $fRaynetCrmRestClient;

    public function __construct($instanceName, $userName, $apiKey) {
        $this->fRaynetCrmRestClient = new RaynetCrmRestClient($instanceName, $userName, $apiKey);
    }

    public function createComplexActivityWithPersonOrCompanyContext(array $activityData, array $personData, array $companyData, $positionToCompany) {
        $companyId = null;
        if ($this->isCompanyPresentInCompanyData($companyData)) {
            $companyId = $this->fRaynetCrmRestClient->findCompanyId($companyData['name']);
            if (is_null($companyId)) {
                $companyId = $this->fRaynetCrmRestClient->createCompany($companyData);
            }

            $activityData['company'] = $companyId;
        }

        if ($this->isPersonPresentInPersonData($personData)) {
            if (!is_null($companyId)) {
                $companyAddressId = $this->fRaynetCrmRestClient->findCompanyPrimaryAddressId($companyId);
                $personData['relationship'] = array(
                    'company'           => $companyId,
                    'companyAddress'    => $companyAddressId,
                    'type'              => $positionToCompany
                );
            }

            $person = $this->fRaynetCrmRestClient->findPerson($personData['contactInfo']['email']);
            if (is_null($person)) {
                $person = $this->fRaynetCrmRestClient->createPerson($personData);
            }

            $activityData['person'] = $person;
        }

        $this->fRaynetCrmRestClient->createActivity('task', $activityData);
    }

    /**
     * You may specify a logic to determine whether it is worth to create a new person e.g. all required fields exist.
     *
     * @param $personData
     * @return bool
     */
    private function isPersonPresentInPersonData(array $personData) {
        return true;
    }

    /**
     * You may specify a logic to determine whether it is worth to create a new company e.g. all required fields exist.
     *
     * @param $companyData
     * @return bool
     */
    private function isCompanyPresentInCompanyData(array $companyData) {
        return true;
    }

} 