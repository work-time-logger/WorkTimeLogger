<?php

namespace App\Http\Responses;


use DomainException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use KDuma\ContentNegotiableResponses\BaseArrayResponse;
use KDuma\ContentNegotiableResponses\Interfaces\HtmlResponseInterface;
use KDuma\ContentNegotiableResponses\Interfaces\JsonResponseInterface;

class DomainExceptionResponse extends BaseArrayResponse // implements HtmlResponseInterface
{
    /**
     * @var null|int HTTP Response Code
     */
    protected $responseCode = 422;
    
    /**
     * @var DomainException
     */
    private $exception;

    public function __construct(DomainException $exception)
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
        return JsonResponseInterface::class;
    }
    
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function toHtmlResponse($request)
    {
        $this->responseCode = null;
        
        flash()->error($this->exception->getMessage());
        
        return back();
    }
}
