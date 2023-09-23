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
            $this->dyeDragData[$player->getName()] = $item->getColor();
        } elseif (isset($this->dyeDragData[$player->getName()])) {
            $dyeColor = $this->dyeDragData[$player->getName()];
            $dyeItem = new Dye($dyeColor);
            $shulkerBoxItem = $this->createColoredShulkerBoxItem($dyeColor);
            $this->transferCustomData($item, $shulkerBoxItem);
            $player->getInventory()->remove($dyeItem);
            $player->getInventory()->setItemInHand($shulkerBoxItem);
            $player->sendMessage("Shulker Box color changed!");

            unset($this->dyeDragData[$player->getName()]);
        }
    }

    private function createColoredShulkerBoxItem(int $dyeColor): Dye {

        $shulkerBoxItem = Dye::get(Item::SHULKER_BOX);
        $nbt = new CompoundTag("", [
            new StringTag(Dye::TAG_COLOR, $dyeColor)
        ]);
        $shulkerBoxItem->setNamedTag($nbt);

        return $shulkerBoxItem;
    }
}
