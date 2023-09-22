<?php

declare(strict_types=1);

namespace Terpz710\ShulkerDye;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Dye;
use pocketmine\block\tile\ShulkerBox;
use pocketmine\player\Player;

class EventListener implements Listener {

    public function onPlayerInteract(PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $item = $player->getInventory()->getItemInHand();

        if ($item instanceof Dye) {
            $dyeColor = $item->getColor();
            $tile = $block->getWorld()->getTile($block);

            if ($tile instanceof ShulkerBox) {
                $tile->setColor($dyeColor);
                $player->sendMessage("Shulker Box color changed!");
            }
        }
    }
}
