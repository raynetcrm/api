<?

namespace raynet;


use Zend\Http\Client;

class CreateComplexActivityFacade {

    private $fRaynetCrmRestClient;

    public function __construct($instanceName, $userName, $apiKey) {
        $this->fRaynetCrmRestClient = new RaynetCrmRestClient($instanceName, $userName, $apiKey);
    }

    /**
     * Creates an activity with company and person context records. If company does not exist insertion of the record is attempted. Same with the person.
     * API also creates relationship between company and person if possible. If any record is found, then context for activity is created (no new record for person nor company is created).
     * For any further details check https://s3-eu-west-1.amazonaws.com/static-raynet/webroot/api-doc.html
     * as the class is based on RAYNET Cloud CRM REST API.
     *
     * @param array $activityData array containing an activity to be created, must contain at least: priority, title, scheduledFrom, scheduledTill
     * @param array $personData array containing a person to be created, must contain at least: firstName, lastName, owner, contactInfo -> email
     * @param array $companyData array containing a company to be created, must contain at least: name, owner, rating, state, role and one address with a name specified.
     * @param $positionToCompany string a position for newly created relationship between company and person
     */
    public function createComplexTaskWithPersonOrCompanyContext(array $activityData, array $personData, array $companyData, $positionToCompany) {
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