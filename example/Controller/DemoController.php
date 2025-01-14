<?php

declare(strict_types=1);

namespace HyperfExample\ApiDocs\Controller;

use App\Model\Activity;
use Hyperf\ApiDocs\Annotation\Api;
use Hyperf\ApiDocs\Annotation\ApiFormData;
use Hyperf\ApiDocs\Annotation\ApiHeader;
use Hyperf\ApiDocs\Annotation\ApiOperation;
use Hyperf\ApiDocs\Annotation\ApiResponse;
use Hyperf\Database\Model\Relations\HasOne;
use Hyperf\DTO\Annotation\Contracts\RequestBody;
use Hyperf\DTO\Annotation\Contracts\RequestFormData;
use Hyperf\DTO\Annotation\Contracts\RequestHeader;
use Hyperf\DTO\Annotation\Contracts\RequestQuery;
use Hyperf\DTO\Annotation\Contracts\Valid;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PatchMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use HyperfExample\ApiDocs\DTO\Address;
use HyperfExample\ApiDocs\DTO\Header\DemoToken;
use HyperfExample\ApiDocs\DTO\PageQuery;
use HyperfExample\ApiDocs\DTO\Request\DemoBodyRequest;
use HyperfExample\ApiDocs\DTO\Request\DemoFormData;
use HyperfExample\ApiDocs\DTO\Request\DemoQuery;
use HyperfExample\ApiDocs\DTO\Response\ActivityPage;
use HyperfExample\ApiDocs\DTO\Response\ActivityResponse;
use HyperfExample\ApiDocs\DTO\Response\Contact;
use JetBrains\PhpStorm\Deprecated;

#[Controller(prefix: '/exampleDemo')]
#[Api(tags: 'demo管理', position: 1)]
#[ApiHeader('apiHeader')]
class DemoController
{
    #[ApiOperation('1:查询')]
    #[PostMapping(path: 'query')]
    public function query(#[RequestBody] #[Valid] DemoQuery $request): Contact
    {
        var_dump($request);
        return new Contact();
    }

    #[ApiOperation('2:提交body数据和get参数')]
    #[PutMapping(path: 'add')]
    public function add(#[RequestBody] #[Valid] DemoBodyRequest $request, #[RequestQuery] DemoQuery $query): ActivityResponse
    {
        dump($request);
        dump($query);
        return new ActivityResponse();
    }

    #[ApiOperation('3:表单提交')]
    #[PostMapping(path: 'fromData')]
    #[ApiFormData(name: 'photo', type: 'file')]
    #[ApiFormData(name: 'photo2', type: 'file')]
    #[ApiResponse(code: '200', description: 'success', className: Address::class, type: 'array')]
    #[ApiResponse(code: '201', className: Address::class)]
    #[ApiResponse(code: '203', description: 'success', type: 'int')]
    #[ApiResponse(code: '204', type: 'bool')]
    public function fromData(#[RequestFormData] DemoFormData $formData): object
    {
//        $file = $this->request->file('photo');
//        var_dump($file);
        var_dump($formData);
        return new Address();
    }

    #[ApiOperation('4:查询单体记录')]
    #[GetMapping(path: 'find/{id}/and/{in}')]
    #[ApiHeader('test2')]
    public function find(int $id, float $in): array
    {
        return ['$id' => $id, '$in' => $in];
    }

    #[ApiOperation('5:分页')]
    #[GetMapping(path: 'page')]
    public function page(#[RequestQuery] PageQuery $pageQuery): ActivityPage
    {
        $model = Activity::with(['activityUser' => function ($query) {
            /* @var HasOne $query */
            $query->orderBy('id', 'desc')->with(['case']);
        }])
            ->paginate($pageQuery->getSize());
        return ActivityPage::from($model);
    }

    #[ApiOperation('6:更新')]
    #[PutMapping(path: 'update/{id}')]
    public function update(int $id): int
    {
        return $id;
    }

    #[ApiOperation('7:删除')]
    #[DeleteMapping(path: 'delete/{id}')]
    public function delete(int $id): int
    {
        return $id;
    }

    #[ApiOperation('patch方法')]
    #[PatchMapping(path: 'patch/{id}')]
    #[Deprecated]
    public function patch(int $id)
    {
        return 55;
    }

    #[ApiOperation('获取请求头')]
    #[PostMapping(path: 'getHeader/{id}')]
    #[Deprecated]
    public function getHeader(#[RequestHeader] #[Valid] DemoToken $header): DemoToken
    {
        dump($header);
        return $header;
    }

    #[ApiOperation('返回 obj')]
    #[GetMapping(path: 'obj')]
    #[Deprecated]
    public function obj(): object
    {
        return new self();
    }
}
