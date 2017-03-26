<?php

namespace FreeElephants\Phalette;

use FreeElephants\HexoNardsGameServer\Phalette\ColorInterface;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class RGB implements ColorInterface
{
    /**
     * @var int
     */
    private $red;
    /**
     * @var int
     */
    private $green;
    /**
     * @var int
     */
    private $blue;

    public function __construct(int $red, int $green, int $blue)
    {
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
    }

    public function toHtmlString(): string
    {
        $htmlString = sprintf('#%s%s%s', dechex($this->red), dechex($this->green), dechex($this->blue));
        return strtoupper($htmlString);
    }
}