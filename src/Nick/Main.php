<?php

namespace Nick;

use Nick\NickCmd;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {
    
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->register("nickname", new NickCmd("nickname", $this));
    }
    
    public function onJoin(PlayerJoinEvent $e) {
        $p = $e->getPlayer();
        $nicks = new Config($this->getDataFolder()."nicks.yml", Config::YAML, [strtolower($p->getName()) => $p->getName()]);
        $nicks->save();
        $name = $nicks->get(strtolower($p->getName()));
        if($name == $p->getName()) {
            $p->setDisplayName($p->getName());
            $p->setNameTag($p->getName());
        } else {
            $p->setDisplayName($name . "*§r");
            $p->setNameTag($name . "*§r§d");
        }
    }
    
    public function onQuit(PlayerQuitEvent $e) {
        $p = $e->getPlayer();
        $nicks = new Config($this->getDataFolder()."nicks.yml", Config::YAML, [strtolower($p->getName()) => $p->getName()]);
        $nicks->save();
        $name = $nicks->get(strtolower($p->getName()));
        if(!$name == $p->getName()) {
            $p->setDisplayName($name . "*§r");
            $p->setNameTag($name . "*§r§d");
        }
    }
}