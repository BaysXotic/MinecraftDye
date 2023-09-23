<?php

declare(strict_types=1);

namespace Terpz710\ShulkerDye;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\item\Dye;
use pocketmine\item\Item;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\block\tile\ShulkerBox;
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

            $shulkerBoxItem = new ShulkerBox();
            $shulkerBoxItem->setNamedTagEntry(new StringTag("Color", $dyeColor->getName()));

            $tile = $player->getLevel()->getTile($player->floor()->subtract(0, 1));
            if ($tile instanceof ShulkerBox) {
                $tile->setColor($dyeColor);
            }

            $player->getInventory()->remove($dyeItem);
            $player->getInventory()->setItemInHand($shulkerBoxItem);
            $player->sendMessage("Shulker Box color changed!");

            unset($this->dyeDragData[$player->getName()]);
        }
    }
}
