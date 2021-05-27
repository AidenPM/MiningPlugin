<?php

namespace pju6791\MiningPlugin;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

use pju6791\MiningPlugin\command\MiningCommand;
use pju6791\MiningPlugin\listener\EventListener;

use function mkdir;
use function strtolower;

class MiningPlugin extends PluginBase {

    use SingletonTrait;

    public $Mining, $db;

    public function onLoad() {
        self::setInstance($this);
    }

    public function onEnable() {

        $this->getServer()->getCommandMap()->register('pju6791', new MiningCommand($this));

        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

        @mkdir($this->getDataFolder());
        $this->Mining = new Config($this->getDataFolder() . "Mining.json", Config::JSON);
        $this->db = $this->Mining->getAll();
    }

    public function getBreakCount(string $name) :int {

        return $this->db[strtolower($name)]["count"];
    }

    public function getMiningSkill(string $name) :int {

        return $this->db[strtolower($name)]["skill"];
    }

    public function addBreakCount(string $name, int $count) {

        $this->db[strtolower($name)]["count"] += $count;
        $this->onSave();
    }

    public function addMiningSkill(string $name, int $skill) {

        $this->db[strtolower($name)]["skill"] += $skill;
        $this->onSave();
    }

    public function onSave() {

        if($this->Mining instanceof Config) {
            $this->Mining->setAll($this->db);
            $this->Mining->save();
        }
    }
}
