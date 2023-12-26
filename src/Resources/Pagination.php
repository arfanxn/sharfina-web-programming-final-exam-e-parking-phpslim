<?php

namespace App\Resources;

use Purl\Url;

class Pagination implements \App\Interfaces\ArrayableInterface
{
    private int $page;
    private int $perPage;
    private ?int $lastPage;
    private ?string $prevUrlStr;
    private string $currentUrlStr;
    private ?string $nextUrlStr;
    private array $data;

    /**
     * new instantiates instance of ResponseBody
     *
     * @return self
     */
    public static function new(): self
    {
        return new self();
    }

    /**
     * fillMetadata fills pagination metadata
     * 
     * @param int $page
     * @param int $perPage
     * @param ?int $lastPage
     * @param ?string $urlStr
     * @return self
     */
    public function fillMetadata(int $page, int $perPage = 10, ?int $lastPage = null, ?string $urlStr = null): self
    {
        $this->page = $page;
        $this->perPage = $perPage;
        $this->lastPage = $lastPage;

        if (is_null($urlStr)) { // if null, use the current url string
            $urlStr = Url::fromCurrent()->__toString();
        }

        $currentUrl = new Url($urlStr);
        $currentUrl->query->set('page', $page);
        $currentUrl->query->set('per_page', $perPage);
        $this->currentUrlStr = $currentUrl->__toString();

        if ($page > 1) {
            $prevUrl = new Url($this->currentUrlStr);
            $prevUrl->query->set('page', $page - 1);
            $this->prevUrlStr = $prevUrl->__toString();
        }

        if ((!is_null($lastPage) && ($page < $lastPage)) || is_null($lastPage)) {
            $nextUrl = new Url($this->currentUrlStr);
            $nextUrl->query->set('page', $page + 1);
            $this->nextUrlStr = $nextUrl->__toString();
        } else {
            $this->nextUrlStr = null;
        }

        return $this;
    }

    /**
     * setData sets the pagination data
     * 
     * @param array $data
     */
    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function toArray(): array
    {
        $arr =  [
            'prev_url' => $this->prevUrlStr ?? null,
            'current_url' => $this->currentUrlStr,
            'next_url' => $this->nextUrlStr ?? null,
            'page' => $this->page,
            'per_page' => $this->perPage,
            'last_page' => $this->lastPage ?? null,
            'data' => array_map(function ($d) {
                if ($d instanceof \App\Interfaces\ArrayableInterface) {
                    return $d->toArray();
                }
                return $d;
            }, $this->data)
        ];

        return $arr;
    }
}
