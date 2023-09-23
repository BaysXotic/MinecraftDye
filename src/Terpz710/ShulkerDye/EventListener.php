<?php

declare(strict_types=1);

namespace Terpz710\ShulkerDye;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\item\Dye;
use pocketmine\player\Player;
use pocketmine\block\BlockIdentifier;

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

            $shulkerBoxIdentifier = new BlockIdentifier(BlockIdentifier::SHULKER_BOX, $dyeColor->getName());

            $player->getInventory()->removeItem($dyeItem);
            $player->getInventory()->addItem($shulkerBoxIdentifier->getBlockItem());
            $player->sendMessage("Shulker Box color changed!");

            unset($this->dyeDragData[$player->getName()]);
        }
    }
}
