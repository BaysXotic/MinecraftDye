<?php

declare(strict_types=1);

namespace Terpz710\ShulkerDye;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\item\Dye;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\item\Item;
use pocketmine\tile\ShulkerBox;
use pocketmine\block\Block;

class EventListener implements Listener {

    private $dyeDragData = [];

    public function onPlayerItemHeld(PlayerItemHeldEvent $event) {
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();

        if ($item instanceof Dye) {
            $this->dyeDragData[$player->getName()] = $item;
        } elseif (isset($this->dyeDragData[$player->getName()])) {
            $dyeItem = $this->dyeDragData[$player->getName()];
            $dyeColor = $dyeItem->getDyeColor();

            $shulkerBox = new ShulkerBox(Block::get(Block::SHULKER_BOX), $dyeColor);

            $player->getInventory()->removeItem($dyeItem);
            $player->getInventory()->addItem($shulkerBox->getBlock());
            $player->sendMessage("Shulker Box color changed!");

            unset($this->dyeDragData[$player->getName()]);
        }
    }
}
