<?php

declare(strict_types=1);

namespace Terpz710\ShulkerDye;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Dye;
use pocketmine\block\tile\ShulkerBox;
use pocketmine\player\Player;
use pocketmine\nbt\tag\CompoundTag;

class EventListener implements Listener {

    public function onPlayerInteract(PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $item = $player->getInventory()->getItemInHand();

        if ($item instanceof Dye) {
            $dyeColor = $item->getColor();
            $tile = $block->getWorld()->getTile($block);

            if ($tile instanceof ShulkerBox) {
                $this->changeColor($tile, $dyeColor);
                $player->sendMessage("Shulker Box color changed!");
            }
        }
    }

    public function changeColor(ShulkerBox $shulkerBox, int $color) {

        $shulkerBox->setColor($color);
        $nbt = $shulkerBox->getNamedTag() ?? new CompoundTag();
        $nbt->setByte("Color", $color);
        $shulkerBox->setNamedTag($nbt);
    }
}
