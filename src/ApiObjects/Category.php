<?php

namespace JmaDsm\GatewayClient\ApiObjects;

use JmaDsm\GatewayClient\ApiObjectResult;
use JmaDsm\GatewayClient\Client;

class Category
{
    private static string $apiPath = '/product/api/v1';

    /**
     * Returns all categories
     *
     * @param int $page
     * @param $since
     * @return JmaDsm\GatewayClient\ApiObjectResult;
     */
    public static function all(int $page = 1, $since = null)
    {
        $result = json_decode(Client::getInstance()->get(self::$apiPath . '/categories', ['page' => $page, 'since' => $since]));
        $categories = $result->data;

        // Iterate through remaining category pages
        while ($result->current_page <= $result->last_page) {
            $result = json_decode(Client::getInstance()->get(self::$apiPath . '/categories', ['page' => ($result->current_page + 1), 'since' => $since]));
            $categories = array_merge($categories, $result->data);
        }

        return new ApiObjectResult($categories);
    }

    /**
     * Returns specific category
     *
     * @param $id
     * @return ApiObjectResult
     */
    public static function get($id)
    {
        $result = json_decode(Client::getInstance()->get(self::$apiPath . '/categories/' . $id));

        return new ApiObjectResult($result->data);
    }

    /**
     * Returns categories changed since $from date. Defaults to page 1
     *
     * @param $since
     * @param int $page
     * @return ApiObjectResult
     */
    public static function since($since, $page = 1)
    {
        return Category::all($page, $since);
    }
}
