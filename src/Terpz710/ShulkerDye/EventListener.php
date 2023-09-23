<?php

/*
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_-_/ _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * | |__| (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_____\___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 */

declare(strict_types=1);

namespace Terpz710\ShulkerDye;

use pocketmine\block\BlockIdentifier;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\item\Dye;
use pocketmine\player\Player;
use pocketmine\block\ShulkerBox as ShulkerBoxBlock;

class EventListener implements Listener {

    private $dyeDragData = [];

    public function onPlayerItemHeld(PlayerItemHeldEvent $event) {
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();

        if ($item instanceof Dye) {
            $this->dyeDragData[$player->getName()] = $item;
        } elseif (isset($this->dyeDragData[$player->getName()])) {
            $dyeItem = $this->dyeDragData[$player->getName()];
            $dyeColor = $dyeItem->getColor();

            $blockIdentifier = new BlockIdentifier(ShulkerBoxBlock::WHITE + $dyeColor->getId());
            $player->getLevel()->setBlock($player->floor()->subtract(0, 1), $blockIdentifier);

            $player->getInventory()->remove($dyeItem);
            $player->sendMessage("Shulker Box color changed!");

            unset($this->dyeDragData[$player->getName()]);
        }
    }
}
