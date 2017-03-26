<?php

namespace FreeElephants\HexoNardsGameServer\Phalette;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
interface ColorInterface
{
    public function toHtmlString(): string;
}