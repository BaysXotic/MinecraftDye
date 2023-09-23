<?php

declare(strict_types=1);

namespace Terpz710\ShulkerDye;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\item\Dye;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
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
            $dyeColor = $this->getDyeColor($dyeItem);
            $shulkerBoxItem = $this->createColoredShulkerBoxItem($dyeColor);
            $player->getInventory()->remove($dyeItem); 
            $player->getInventory()->setItemInHand($shulkerBoxItem);
            $player->sendMessage("Shulker Box color changed!");

            unset($this->dyeDragData[$player->getName()]);
        }
    }

    private function getDyeColor(Dye $dyeItem): int {
        $nbt = $dyeItem->getNamedTag();
        if ($nbt !== null && $nbt->hasTag(Dye::TAG_COLOR, StringTag::class)) {
            return $nbt->getString(Dye::TAG_COLOR);
        }
        return Dye::COLOR_WHITE;
    }

    private function createColoredShulkerBoxItem(int $dyeColor): Dye {
        $shulkerBoxItem = new Dye($dyeColor);
        $nbt = new CompoundTag("", [
            new StringTag(Dye::TAG_COLOR, $dyeColor)
        ]);
        $shulkerBoxItem->setNamedTag($nbt);

        return $shulkerBoxItem;
    }
}
