<?php
namespace Beleed\Client;

use Beleed\Client\Exception\HttpException;
use Beleed\Client\Exception\LogicException;
use Beleed\Client\Model\Opportunity;
use Beleed\Client\Model\Contact;
use Beleed\Client\Model\Product;
use Buzz\Client\ClientInterface;
use Buzz\Message\Request;
use Buzz\Message\Response;

class Client
{
    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @param ClientInterface $httpClient
     * @param string $accessToken
     */
    public function __construct(ClientInterface $httpClient, $accessToken)
    {
        $this->httpClient = $httpClient;
        $this->accessToken = $accessToken;

        $this->baseUrl = 'https://beleed.com';
    }

    /**
     * @param Product $product
     *
     * @return Product
     */
    public function createProduct(Product $product)
    {
        $rawProduct = $this->doHttpRequest('POST', 'api/v1/products', array('product' => $product));

        return $this->copyStdClassPropertiesToModel($rawProduct, $product);
    }

    /**
     * @param string $id
     *
     * @return Product
     */
    public function fetchProduct($id)
    {
        $rawProduct = $this->doHttpRequest('GET', sprintf('api/v1/products/%s', $id));

        return $this->copyStdClassPropertiesToModel($rawProduct, new Product);
    }

    /**
     * @param Contact $contact
     *
     * @return Contact
     */
    public function createContact(Contact $contact)
    {
        $rawContact = $this->doHttpRequest('POST', 'api/v1/contacts', array('contact' => $contact));

        return $this->copyStdClassPropertiesToModel($rawContact, $contact);
    }

    /**
     * @param Contact $contact
     *
     * @return Contact
     */
    public function updateContact(Contact $contact)
    {
        $this->doHttpRequest('PATCH', 'api/v1/contacts/' . $contact->id, array('contact' => $contact));
    }

    /**
     * @param string $id
     *
     * @return Contact
     */
    public function fetchContact($id)
    {
        $rawContact = $this->doHttpRequest('GET', sprintf('api/v1/contacts/%s', $id));

        return $this->copyStdClassPropertiesToModel($rawContact, new Contact);
    }

    /**
     * @param $id
     */
    public function deleteContact($id)
    {
        $this->doHttpRequest('DELETE', sprintf('api/v1/contacts/%s', $id));
    }

    /**
     * @param $email
     *
     * @return Contact
     */
    public function fetchContactByEmail($email)
    {
        $rawContact = $this->doHttpRequest('GET', sprintf('api/v1/contacts/%s', $email));

        return $this->copyStdClassPropertiesToModel($rawContact, new Contact);
    }

    /**
     * @param Opportunity $opportunity
     *
     * @return Opportunity
     */
    public function createOpportunity(Opportunity $opportunity)
    {
        $rawOpportunity = $this->doHttpRequest('POST', 'api/v1/opportunities', array('opportunity' => $opportunity));

        return $this->copyStdClassPropertiesToModel($rawOpportunity, $opportunity);
    }

    /**
     * @return Opportunity
     */
    public function fetchOpportunity($id)
    {
        $rawOpportunity = $this->doHttpRequest('GET', sprintf('api/v1/opportunities/%s', $id));

        $opportunity = $this->copyStdClassPropertiesToModel($rawOpportunity, new Opportunity);
        $opportunity->product = $this->copyStdClassPropertiesToModel($opportunity->product, new Product);
        $opportunity->contact = $this->copyStdClassPropertiesToModel($opportunity->contact, new Contact);

        return $opportunity;
    }

    /**
     * @param $method
     * @param $relativeUrl
     * @param array|\stdClass $content
     * @return mixed
     */
    protected function doHttpRequest($method, $relativeUrl, $content = null)
    {
        $relativeUrl = ltrim($relativeUrl, '/');

        $response = new Response();

        $request = new Request($method);
        $request->fromUrl($this->baseUrl . '/' . $relativeUrl);
        $request->addHeader("Authorization: Bearer {$this->accessToken}");
        $request->addHeader("Content-Type: application/json; charset=UTF-8");
        $request->addHeader('Accept: application/json');

        if ($content) {
            $request->setContent(json_encode(array_filter((array) $content)));
        }

        $this->httpClient->send($request, $response);

        return $this->getResult($response);
    }

    /**
     * @param \Buzz\Message\Response $response
     *
     * @throws HttpException if status not OK
     * @throws HttpException if content not json
     *
     * @return \stdClass|\stdClass[]|null
     */
    protected function getResult(Response $response)
    {
        if (false == $response->isSuccessful()) {
            throw new HttpException(
                $response->getStatusCode(),
                sprintf("The api call finished with status %s but it was expected 200. Response content:\n\n%s",
                    $response->getStatusCode(),
                    $response->getContent()
                )
            );
        }

        $content = $response->getContent();
        $result = null;

        if (false == empty($content)) {
            $result = json_decode($response->getContent());
            if (null === $result) {
                throw new LogicException(sprintf(
                    "The response status successful but the content is not valid json:\n\n%s",
                    $response->getContent()
                ));
            }
        }

        return $result;
    }

    /**
     * @param \stdClass $stdUser
     * @param object    $model
     *
     * @return object
     */
    protected function copyStdClassPropertiesToModel(\stdClass $stdUser, $model)
    {
        foreach ($stdUser as $propertyName => $value) {
            $model->$propertyName = $value;
        }

        return $model;
    }
}
