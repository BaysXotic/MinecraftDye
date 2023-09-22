<?php

namespace Terpz710\ShulkerDye;

use pocketmine\event\Listener;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\item\Dye;
use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;
use pocketmine\inventory\ShapelessRecipe;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        
        $this->registerCustomRecipes();
    }

    public function onCraftItem(CraftItemEvent $event) {
        $player = $event->getPlayer();
        $result = $event->getResult();
        
        if ($result->getId() === Item::SHULKER_BOX && $result->getDamage() === 0) {
            
            $ingredients = $event->getInventory()->getContents();
            
            foreach ($this->getCustomRecipes() as $color => $recipe) {
                if ($this->checkRecipe($ingredients, $recipe)) {
                    
                    $result->setDamage($color);
                    
                    $event->setResult($result);
                    
                    $this->removeRecipeIngredients($event->getInventory(), $recipe);
                    
                    $player->sendMessage("Crafted a $color Shulker Box!");
                    break;
                }
            }
        }
    }

    private function registerCustomRecipes() {
        
        $customRecipes = $this->getCustomRecipes();
        
        foreach ($customRecipes as $color => $recipe) {
            $shulkerBox = Item::get(Item::SHULKER_BOX, 0, 1);
            $shulkerBox->setCustomName($color . " Shulker Box");
            
            $recipe = new ShapelessRecipe($shulkerBox, $recipe);
            
            $this->getServer()->getCraftingManager()->registerRecipe($recipe);
        }
    }

    private function getCustomRecipes() {
    return [
        "Red" => [
            Dye::get(Dye::RED),
            Item::get(Item::SHULKER_BOX, 0, 1),
        ],
        "Blue" => [
            Dye::get(Dye::BLUE),
            Item::get(Item::SHULKER_BOX, 0, 1),
        ],
        "Green" => [
            Dye::get(Dye::GREEN),
            Item::get(Item::SHULKER_BOX, 0, 1),
        ],
        "Yellow" => [
            Dye::get(Dye::YELLOW),
            Item::get(Item::SHULKER_BOX, 0, 1),
        ],
        "Purple" => [
            Dye::get(Dye::PURPLE),
            Item::get(Item::SHULKER_BOX, 0, 1),
        ],
        "Cyan" => [
            Dye::get(Dye::CYAN),
            Item::get(Item::SHULKER_BOX, 0, 1),
        ],
        "Light Gray" => [
            Dye::get(Dye::LIGHT_GRAY),
            Item::get(Item::SHULKER_BOX, 0, 1),
        ],
        "Gray" => [
            Dye::get(Dye::GRAY),
            Item::get(Item::SHULKER_BOX, 0, 1),
        ],
        "Pink" => [
            Dye::get(Dye::PINK),
            Item::get(Item::SHULKER_BOX, 0, 1),
        ],
        "Lime" => [
            Dye::get(Dye::LIME),
            Item::get(Item::SHULKER_BOX, 0, 1),
        ],
        "Orange" => [
            Dye::get(Dye::ORANGE),
            Item::get(Item::SHULKER_BOX, 0, 1),
        ],
        "Magenta" => [
            Dye::get(Dye::MAGENTA),
            Item::get(Item::SHULKER_BOX, 0, 1),
        ],
        "Light Blue" => [
            Dye::get(Dye::LIGHT_BLUE),
            Item::get(Item::SHULKER_BOX, 0, 1),
        ],
        "Brown" => [
            Dye::get(Dye::BROWN),
            Item::get(Item::SHULKER_BOX, 0, 1),
        ],
        "Black" => [
            Dye::get(Dye::BLACK),
            Item::get(Item::SHULKER_BOX, 0, 1),
        ],
        "White" => [
            Dye::get(Dye::WHITE),
            Item::get(Item::SHULKER_BOX, 0, 1),
            ],
        ];
    }

    private function checkRecipe(array $ingredients, array $recipe) {
        
        foreach ($recipe as $ingredient) {
            if (!isset($ingredients[$ingredient->getId()])) {
                return false;
            }
            $count = $ingredients[$ingredient->getId()]->getCount();
            if ($count < $ingredient->getCount()) {
                return false;
            }
        }
        return true;
    }

    private function removeRecipeIngredients($inventory, array $recipe) {
       
        foreach ($recipe as $ingredient) {
            $inventory->removeItem($ingredient);
        }
    }
}
