<?php

namespace Terpz710\ShulkerDye;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\tile\ShulkerBox;
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

        $parsedItem = $itemParser->parse($item->getCustomName());
        if ($parsedItem->getId() === 351) {
            $dyeColor = $parsedItem->getDamage();
            $level = $block->getLevel();

            if ($level->getBlockAt($block->x, $block->y, $block->z)->getId() === 219) { // Shulker Box ID is 219
                $tile = $level->getTile($block);

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
}
