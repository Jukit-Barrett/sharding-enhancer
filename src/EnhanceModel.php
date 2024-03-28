<?php

namespace Jukit\ShardingEnhancer;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * 模型增强
 */
abstract class EnhanceModel extends Model
{
    //use Positioner;

    /**
     * @var string 与表关联的主键
     */
    protected $primaryKey = 'id';

    /**
     * @var string 模型日期的存储格式
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * @var string 时间格式
     */
    protected $serializeDateFormat = 'Y-m-d H:i:s';

    /**
     * 表字段
     * @return array
     */
    public static function getFields(): array
    {
        return [];
    }

    /**
     * 下划线字段
     * @return array
     */
    public static function getSnake(): array
    {
        $fields = static::getFields();

        $snakeFields = [];

        foreach ($fields as $field) {
            $snakeFields[] = Str::snake($field);
        }

        return $snakeFields;
    }

    /**
     * 驼峰字段
     * @return array
     */
    public static function getCamel(): array
    {
        $fields = static::getFields();

        $camelFields = [];

        foreach ($fields as $field) {
            $camelFields[] = Str::camel($field);
        }

        return $camelFields;
    }

    /**
     * 格式化日期
     *
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format($this->serializeDateFormat);
    }
}
