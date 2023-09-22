<?php

declare(strict_types=1);

namespace Terpz710\ShulkerDye;

use pocketmine\block\tile\ShulkerBox;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Dye;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;

class EventListener extends PluginBase implements Listener {

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("YourPluginName has been enabled!");
    }

    public function onPlayerInteract(PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $item = $player->getInventory()->getItemInHand();

        if ($item instanceof Dye) {
            $dyeColor = $item->getColor();
            $tile = $block->getWorld()->getTile($block);

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
