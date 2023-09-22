<?php

namespace Terpz710\ShulkerDye;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

class Main extends PluginBase {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    }
}
