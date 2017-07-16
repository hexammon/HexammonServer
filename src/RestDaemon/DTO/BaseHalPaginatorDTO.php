<?php

namespace FreeElephants\RestDaemon\DTO;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class BaseHalPaginatorDTO implements HalPaginatorDTOInterface
{

    /**
     * @var string
     */
    private $paginationLinkPattern;
    /**
     * @var int
     */
    private $total;
    /**
     * @var int
     */
    private $limit;
    /**
     * @var int
     */
    private $offset;

    public function __construct(string $paginationLinkPattern, int $total, int $limit, int $offset)
    {
        $this->paginationLinkPattern = $paginationLinkPattern;
        $this->total = $total;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getBasePaginationLinkHref(): string
    {
        return $this->paginationLinkPattern;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }
}