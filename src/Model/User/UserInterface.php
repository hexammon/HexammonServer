<?php

namespace FreeElephants\HexoNardsGameServer\Model\User;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
interface UserInterface
{

    public function getId();

    public function getLogin(): string;
}