<?php

namespace FreeElephants\RestDaemon\DTO;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
interface HalPaginatorDTOInterface
{
    public function getTotal(): int;

    public function getBasePaginationLinkHref(): string;

    public function getLimit(): int;

    public function getOffset(): int;
}