<?php
namespace Beleed\Client;

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
     * @param ClientInterface $httpClient
     * @param string $accessToken
     */
    public function __construct(ClientInterface $httpClient, $accessToken)
    {
        $this->httpClient = $httpClient;
        $this->accessToken = $accessToken;
    }

    /**
     * @param Product $product
     *
     * @return Product
     */
    public function createProduct(Product $product) {}

    /**
     * @param string $id
     *
     * @return Product
     */
    public function getProduct($id) {}

    /**
     * @param Organization $organization
     *
     * @return Organization
     */
    public function createOrganization(Organization $organization) {}

    /**
     * @param string $id
     *
     * @return Organization
     */
    public function getOrganization($id) {}

    /**
     * @param Opportunity $opportunity
     *
     * @return Opportunity
     */
    public function createOpportunity(Opportunity $opportunity) {}

    /**
     * @return Opportunity
     */
    public function getOpportunity() {}

    protected function doHttpRequest($method, $relativeUrl, array $content = null)
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

        $this->client->send($request, $response);

        return $this->getResult($response);
    }

    /**
     * @param \Buzz\Message\Response $response
     *
     * @throws RequestFailedException if status not OK
     * @throws RequestFailedException if content not json
     *
     * @return array|\stdClass
     */
    protected function getResult(Response $response)
    {
        if (false == $response->isSuccessful()) {
            $exceptionClass = RequestFailedException::getExceptionClassByStatusCode($response->getStatusCode());

            $exception = new $exceptionClass(sprintf("The api call finished with status %s but it was expected 200. Response content:\n\n%s",
                $response->getStatusCode(),
                $response->getContent()
            ));

            $exception->setStatusCode($response->getStatusCode());
            $exception->setContent('application/json' == $response->getHeader('Content-Type') ?
                    json_decode($response->getContent()) : $response->getContent()
            );

            throw $exception;
        }

        //assume no content were sent back
        if (false == $response->isOk()) {
            return;
        }

        $result = json_decode($response->getContent());
        if (null === $result) {
            $exceptionClass = RequestFailedException::getExceptionClassByStatusCode($response->getStatusCode());

            throw new $exceptionClass("The content is not valid json.\n\n".$response);
        }

        return $result;
    }
}
