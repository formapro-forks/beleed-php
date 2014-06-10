<?php
namespace Beleed\Client;

use Beleed\Client\Exception\Http\HttpException;
use Beleed\Client\Exception\LogicException;
use Beleed\Client\Model\Opportunity;
use Beleed\Client\Model\Organization;
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

        $this->baseUrl = 'http://beleed.com';
    }

    /**
     * @param Product $product
     *
     * @return Product
     */
    public function createProduct(Product $product)
    {
        $rawProduct = $this->doHttpRequest('POST', 'api/v1/products', $product);

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
     * @param Organization $organization
     *
     * @return Organization
     */
    public function createOrganization(Organization $organization)
    {
        $rawOrganization = $this->doHttpRequest('POST', 'api/v1/organizations', $organization);

        return $this->copyStdClassPropertiesToModel($rawOrganization, $organization);
    }

    /**
     * @param string $id
     *
     * @return Organization
     */
    public function fetchOrganization($id)
    {
        $rawOrganization = $this->doHttpRequest('GET', sprintf('api/v1/organizations/%s', $id));

        return $this->copyStdClassPropertiesToModel($rawOrganization, new Organization);
    }

    /**
     * @param Opportunity $opportunity
     *
     * @return Opportunity
     */
    public function createOpportunity(Opportunity $opportunity)
    {
        $rawOpportunity = $this->doHttpRequest('POST', 'api/v1/opportunities', array('data' => $opportunity));

        return $this->copyStdClassPropertiesToModel($rawOpportunity, $opportunity);
    }

    /**
     * @return Opportunity
     */
    public function fetchOpportunity($id)
    {
        $rawOpportunity = $this->doHttpRequest('GET', sprintf('api/v1/opportunities/%s', $id));

        return $this->copyStdClassPropertiesToModel($rawOpportunity, new Opportunity);
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
        $request->addHeader('Accept: application/json');

        if ($content) {
            $request->setContent(json_encode(array_filter($content)));
        }

//        echo $request; die;
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
