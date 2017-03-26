<?php

namespace FreeElephants\HexammonServer\Model\Board;

use FreeElephants\HexoNards\Board\Board;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class BoardConfigDTO
{

    public $numberOfRows;
    public $numberOfColumns;
    public $type;

    public function __construct(Board $board)
    {
        $this->numberOfRows = count($board->getRows());
        $this->numberOfColumns = count($board->getColumns());
        $this->type = $board->getType();
    }
}