<?php

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
