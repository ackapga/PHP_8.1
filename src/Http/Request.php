<?php

namespace Ackapga\Habrahabr\Http;

use Ackapga\Habrahabr\Exceptions\HttpException;
use JsonException;

class Request
{
    public function __construct(
        private array  $get,
        private array  $server,
        private string $body,
    )
    {
    }

    /**
     * Извлекает нужный Метод
     * @return string
     * @throws HttpException
     */
    public function method(): string
    {
        if (!array_key_exists('REQUEST_METHOD', $this->server)) {
            throw new HttpException('Не удается получить метод из запроса!');
        }
        return $this->server['REQUEST_METHOD'];
    }

    /**
     * Метод для получения массива, сформированного из json-форматированного тела запроса
     * @return array
     * @throws HttpException
     */
    public function jsonBody(): array
    {
        try {
            $data = json_decode($this->body, associative: true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            throw new HttpException("Невозможно декодировать(расшифровать) тело JSON!");
        }
        if (!is_array($data)) {
            throw new HttpException("Не Массив/Объект в теле JSON");
        }
        return $data;
    }

    /**
     * Метод для получения отдельного поля из json-форматированного тела запроса
     * @param string $field
     * @return mixed
     * @throws HttpException
     */
    public function jsonBodyField(string $field): mixed
    {
        $data = $this->jsonBody();
        if (!array_key_exists($field, $data)) {
            throw new HttpException("Нет такого поля: $field");
        }
        if (empty($data[$field])) {
            throw new HttpException("Пустое поле: $field");
        }
        return $data[$field];
    }


    /**
     * Метод для получения пути запроса http://example.com /some/page ?x=1&y=acb --->>> '/some/page'
     * @return string
     * @throws HttpException
     */
    public function path(): string
    {
        if (!array_key_exists('REQUEST_URI', $this->server)) {
            throw new HttpException('Не удается получить путь из запроса(Request)');
        }

        $components = parse_url($this->server['REQUEST_URI']);

        if (!is_array($components) || !array_key_exists('path', $components)) {
            throw new HttpException('Не удается получить путь из запроса(Request)');
        }
        return $components['path'];
    }

    /**
     * Метод для получения ЗНАЧЕНИЯ определённого параметра строки запроса
     * @param string $param
     * @return string
     * @throws HttpException
     */
    public function query(string $param): string
    {
        if (!array_key_exists($param, $this->get)) {
            throw new HttpException("Нехватает такого параметра запроса в запросе(Request): $param");
        }

        $value = trim($this->get[$param]);

        if (empty($value)) {
            throw new HttpException("Пустой параметр запроса в запросе(Request): $param");
        }
        return $value;
    }

    /**
     * Метод для получения ЗНАЧЕНИЯ Определённого заголовка
     * @param string $header
     * @return string
     * @throws HttpException
     */
    public function header(string $header): string
    {
        $headerName = mb_strtoupper("http_" . str_replace('-', '_', $header));
        if (!array_key_exists($headerName, $this->server)) {
            throw new HttpException("Нет такого заголовка в запросе(Request): $header");
        }

        $value = trim($this->server[$headerName]);

        if (empty($value)) {
            throw new HttpException("Пустой заголовок в запросе(Request): $header");
        }

        return $value;
    }
}
