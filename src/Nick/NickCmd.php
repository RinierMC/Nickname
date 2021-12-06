<?php

namespace Nick;

use Nick\Main;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\utils\Config;
use pocketmine\plugin\Plugin;

class NickCmd extends PluginCommand{
    
	public $plugin;

	public function __construct($name, Main $plugin) {
        parent::__construct($name, $plugin);
        $this->setDescription("Change your in-game name");
        $this->setUsage("/nickname <name|remove>");
        $this->setAliases(["nick"]);
		$this->setPermission("nick.command");
		$this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool{
        if (!$sender->hasPermission("nick.command")) {
	      	$sender->sendMessage("§cYou do not have permission");
            return false;
        }
        if (count($args) < 1) {
			$sender->sendMessage("§l§7(§a!§7) §r§eUsage: §a/nickname <name|remove>");
            return false;
        }
		if (strlen($args[0]) > 15) {
            $sender->sendMessage("§l§7(§a!§7) §r§aNickname must not be longer than 15 letter's!");
            return false;
        }
        if (strlen($args[0]) < 5) {
            $sender->sendMessage("§l§7(§a!§7) §r§aNickname must be longer than 5 characters!");
            return false;
        }
        if($args[0] == "{msg}") {
            $sender->sendMessage("§l§7(§a!§7) §r§aYou cannot use this nickname!");
            return false;
        }
		if ($args[0] == "clear" || $args[0] == "remove") {
			$nicks = new Config($this->plugin->getDataFolder() . strtolower($sender->getName()) . ".yml", Config::YAML);
			$nicks->set($sender->getName(), $sender->getName());
			$nicks->save();
            $sender->setDisplayName($sender->getName());
            $sender->setNameTag($sender->getName());
            $sender->sendMessage("§l§7(§a!§7) §r§aYour Nick Have Been Removed");
            return true;
        }
		$nicks = new Config($this->plugin->getDataFolder()."nicks.yml", Config::YAML);
	    $nicks->set($sender->getName(), $args[0]."*§r");
	    $nicks->save();
        $sender->setDisplayName($args[0] . "*§r");
        $sender->setNameTag($args[0] . "*§r§d");
        $sender->sendMessage("§l§7(§a!§7) §r§aYour Nick Has Been Set To ".$args[0]);
		return true;
    }
}