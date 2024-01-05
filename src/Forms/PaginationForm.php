<?php

namespace App\Forms;

class PaginationForm
{
    use \App\Traits\FormTrait;

    private int $page;
    private int $perPage;
    private ?string $keyword;

    public function getRules(): array
    {
        return [
            'page' => 'nullable|numeric',
            'per_page' => 'nullable|numeric',
            'keyword' => 'nullable',
        ];
    }

    /**
     * @return string
     */
    public function getPage(): string
    {
        return intval(intval($this->page) == 0 ? 1 : $this->page);
    }
    /**
     * @param mixed $page
     * @return void
     */
    public function setPage(mixed $page): void
    {
        $this->page = intval($page);
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return intval(intval($this->perPage) == 0 ? 10 : $this->perPage);
    }
    /**
     * @param mixed $perPage
     * @return void
     */
    public function setPerPage(mixed $perPage): void
    {
        $this->perPage = intval($perPage);
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->getPerPage();
    }
    /**
     * @return int
     */
    public function getOffset(): int
    {
        return ($this->getPage() - 1) * $this->getPerPage();;
    }


    /**
     * @return ?string
     */
    public function getKeyword(): ?string
    {
        return $this->keyword ?? null;
    }
    /**
     * @param ?string $keyword
     * @return void
     */
    public function setKeyword(?string $keyword): void
    {
        $this->keyword = $keyword == '' ? null : $keyword;
    }
}
