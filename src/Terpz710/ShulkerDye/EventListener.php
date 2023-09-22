<?php

namespace Terpz710\ShulkerDye;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\tile\ShulkerBox;
use pocketmine\block\Block;
use pocketmine\player\Player;
use pocketmine\item\LegacyStringToItemParser;
use pocketmine\item\StringToItemParser;

class EventListener implements Listener {

    public function onPlayerInteract(PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $item = $player->getInventory()->getItemInHand();

        $itemParser = class_exists(StringToItemParser::class)
            ? new StringToItemParser()
            : new LegacyStringToItemParser();

        $parsedItem = $itemParser->parse($item->getName());

        if ($parsedItem->getId() === Item::DYE) {
            $dyeColor = $parsedItem->getDamage();
            $tile = $block->getLevel()->getTile($block);

            if ($tile instanceof ShulkerBox) {
                $nbt = $tile->getNamedTag() ?? new CompoundTag("");
                $nbt->setTag(new ByteTag("Color", $dyeColor));
                $tile->setNamedTag($nbt);
                $tile->saveNBT();
                $player->sendMessage("Shulker Box color changed!");
            }
        }
    }
}
