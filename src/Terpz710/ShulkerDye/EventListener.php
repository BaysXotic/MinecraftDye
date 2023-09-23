<?php

declare(strict_types=1);

namespace Terpz710\ShulkerDye;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\item\Dye;
use pocketmine\item\Item;
use pocketmine\item\ShulkerBox;
use pocketmine\player\Player;

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
            $shulkerBoxItem = new ShulkerBox($dyeColor);

            $player->getInventory()->removeItem(Item::get(Item::DYE, $dyeColor, 1));
            $player->getInventory()->setItemInHand($shulkerBoxItem);
            $player->sendMessage("Shulker Box color changed!");

            unset($this->dyeDragData[$player->getName()]);
        }
    }
}
