<?php

declare(strict_types=1);

namespace Terpz710\ShulkerDye;

use pocketmine\block\Block;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\ShulkerBox as ShulkerBoxBlock;
use pocketmine\block\tile\ShulkerBox as ShulkerBoxTile;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\item\Dye;
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

            $shulkerColor = $dyeColor->getId();

            $blockBelow = $player->getLevel()->getBlock($player->floor()->subtract(0, 1));
            if ($blockBelow instanceof ShulkerBoxBlock && !$blockBelow->getTile() instanceof ShulkerBoxTile) {
                $blockIdentifier = new BlockIdentifier(ShulkerBoxBlock::ID . ":" . $shulkerColor);
                $player->getLevel()->setBlock($blockBelow, $blockIdentifier);

                $player->getInventory()->remove($dyeItem);
                $player->sendMessage("Shulker Box color changed!");
            } else {
                $player->sendMessage("You can only dye undyed Shulker Boxes.");
            }

            unset($this->dyeDragData[$player->getName()]);
        }
    }
}
