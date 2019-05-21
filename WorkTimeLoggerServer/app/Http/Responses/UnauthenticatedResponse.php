<?php

namespace App\Http\Responses;


use App\Http\Resources\HardwareScannerResource;
use App\Models\HardwareScanner;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use KDuma\ContentNegotiableResponses\BaseArrayResponse;
use KDuma\ContentNegotiableResponses\Interfaces\HtmlResponseInterface;

class UnauthenticatedResponse extends BaseArrayResponse implements HtmlResponseInterface
{
    /**
     * @var null|int HTTP Response Code
     */
    protected $responseCode = 401;
    
    /**
     * @var AuthenticationException
     */
    private $exception;

    public function __construct(AuthenticationException $exception)
    {
        $this->exception = $exception;
    }

    protected function getData()
    {
        return ['message' => $this->exception->getMessage()];
    }


    /**
     * @return string
     */
    protected function getDefaultType(): string
    {
        return HtmlResponseInterface::class;
    }
    
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function toHtmlResponse($request)
    {
        $this->responseCode = null;
        
        return redirect()->guest($this->exception->redirectTo() ?? route('login'));
    }
}
