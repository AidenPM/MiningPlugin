<?php

namespace pju6791\MiningPlugin\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pju6791\MiningPlugin\MiningPlugin;

class MiningCommand extends Command {

    public MiningPlugin $plugin;

    public function __construct(MiningPlugin $plugin) {
        parent::__construct("발굴", "나의 발굴정보를 확인합니다.");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $sender->sendMessage("§l§6[알림] §r§f발굴 한 횟수 : {$this->plugin->getBreakCount($sender->getName())}");
        $sender->sendMessage("§l§6[알림] §r§f발굴 숙련도 : {$this->plugin->getMiningSkill($sender->getName())}");
    }
}
