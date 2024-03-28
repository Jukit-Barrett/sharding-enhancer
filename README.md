# Sharding Enhancer

## 项目简介

Laravel Sharding Enhancer 是一个专为 Laravel 框架设计的分表增强扩展，旨在帮助开发人员更有效地管理大型数据表，并提高数据库性能和扩展性。

Sharding Enhancer 提供了一套完善的分表解决方案，能够帮助开发人员轻松应对大数据量和高并发访问的挑战，提高了系统的性能和可扩展性，

是开发大型 Web 应用的理想选择。立即尝试 Sharding Enhancer，提升你的项目性能和扩展性！

## 功能特点

1. 灵活的分表配置： 通过 Laravel Sharding Enhancer，你可以轻松地配置分表的数量和分区范围，根据业务需求灵活调整分表规则。

2. 智能的分表定位： 扩展提供了分表定位功能，能够根据指定的分表因子，自动计算出对应的分表位置，减少了手动计算分表位置的工作量。

3. 简化的分表操作： 使用 Laravel Sharding Enhancer，你可以简单地调用 sharding 方法，将模型数据存储到对应的分表中，无需手动处理分表名称。

4. 高效的分表切换： 当业务需要调整分表规则时，只需修改配置即可，无需修改业务代码，简化了分表切换的操作流程。

5. 灵活的定制选项： 扩展提供了丰富的定制选项，你可以根据具体业务需求定制分表规则、调整分表因子，以及扩展更多分表操作。

## 适用场景：

1. 大型数据表：当数据量庞大时，单表查询性能会受到影响，此时可以使用分表技术将数据分散到多个小表中，提高查询性能。
2. 高并发访问：在高并发场景下，单表数据的读写压力较大，使用分表可以将数据分散到多个表中，降低单表的读写压力，提高系统的并发处理能力。
3. 数据扩展性：随着业务的发展，数据量会逐渐增长，使用分表可以提高数据库的扩展性，保障系统的稳定性和可靠性。

## 使用指南

## 安装

````shell
composer require jukit/sharding-enhancer
````

### 分表接入

- Model

````php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jukit\ShardingEnhancer\EnhanceModel;
use Jukit\ShardingEnhancer\Positioner;

class MailSharding extends EnhanceModel
{
    use HasFactory, SoftDeletes;
    use Positioner;

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'id', 'field_a', 'field_b' ...
    ];

    /**
     * @desc 获取表名
     * @return string
     */
    public function getTable(): string
    {
        return $this->table ?? env('DB_PREFIX', '') . 'mail';
    }
 
    public function getShardMaxCount(): int
    {
        return 64;
    }

    // 分表配置
    public function getShardConfig(): array
    {
        return [
            [
                'partition' => 32,
                'low'       => 0,
                'high'      => 31,
            ],
            [
                'partition' => 64,
                'low'       => 32,
                'high'      => 63,
            ],
        ];
    }

}
````

- Repository

````php
<?php

namespace App\Repositories;

use App\Models\MailSharding;
use Illuminate\Database\Eloquent\Model;
use Jukit\ShardingEnhancer\CrudShardingRepository;

class MailShardingRepository extends CrudShardingRepository
{
    public function __construct(MailSharding $mail)
    {
        // 用于计算分表的值
        $factor = 31;
        $mail->setFactor($factor)->sharding();
        $this->setModel($mail);
    }

    public function selectById(int $id): ?Model
    {
        return $this->getModel()->newQuery()->select('*')->where('id', $id)->first();
    }
}
````

- Client

````php
$mailRepo = new MailShardingRepository(new MailSharding());
$mailId = 1;
$mail = $mailRepo->selectById($mailId);
dump($mail?->toArray());
````

### 无分表接入

- Model

````php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jukit\ShardingEnhancer\EnhanceModel;
use Jukit\ShardingEnhancer\Positioner;

class MailSharding extends EnhanceModel
{
    use HasFactory, SoftDeletes;
    use Positioner;

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'id', 'created_at', 'updated_at', 'deleted_at',
    ];

    /**
     * @desc 获取表名
     * @return string
     */
    public function getTable(): string
    {
        return $this->table ?? env('DB_PREFIX', '') . 'mail';
    }


}
````

- Repository

````php
<?php

namespace App\Repositories;

use App\Models\Mail;
use Illuminate\Database\Eloquent\Model;
use Jukit\ShardingEnhancer\CrudShardingRepository;

class MailRepository extends CrudShardingRepository
{
    public function __construct(Mail $mail)
    {
        $this->setModel($mail);
    }

    public function selectById(int $id): ?Model
    {
        return $this->getModel()->newQuery()->select('*')->where('id', $id)->first();
    }
}
````

- Client

````php
use App\Models\Mail;
use App\Repositories\MailRepository;

$mailRepo = new MailRepository(new Mail());
$mailId = 1;
$mail = $mailRepo->selectById($mailId);
dump($mail?->toArray());
````
