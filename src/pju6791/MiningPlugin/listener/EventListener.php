<?php

namespace pju6791\MiningPlugin\listener;

use pocketmine\event\Listener;

use pocketmine\Player;

use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\block\BlockIds;
use pocketmine\block\BlockFactory;

use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\event\block\BlockBreakEvent;

use pju6791\MiningPlugin\MiningPlugin;
use pju6791\MiningPlugin\task\RegenTask;

use function mt_rand;
use function strtolower;

class EventListener implements Listener
{

    public MiningPlugin $plugin;

    public function __construct(MiningPlugin $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onJoin(PlayerJoinEvent $event)
    {

        $name = $event->getPlayer()->getName();

        if (!isset($this->plugin->db[strtolower($name)])) {
            $this->plugin->db[strtolower($name)]["count"] = 0;
            $this->plugin->db[strtolower($name)]["skill"] = 0;
        }
    }

    public function onBreak(BlockBreakEvent $event)
    {

        $player = $event->getPlayer();
        $block = $event->getBlock();

        $task = $this->plugin->getScheduler();

        if ($player->isSurvival()) {
            if ($block->getId() == BlockIds::BONE_BLOCK) {
                $event->setCancelled(true);
                $task->scheduleDelayedTask(new RegenTask($block), 120);
                $this->plugin->addBreakCount($player->getName(), 1);
                $this->plugin->addMiningSkill($player->getName(), mt_rand(1, 3));
                $block->getLevel()->setBlock($block, BlockFactory::get(BlockIds::COBBLESTONE, 0, null));
                if (mt_rand(1, 100) <= 50) {
                    $this->MiningReward($player);
                }
            }
        }
    }

    public function MiningReward(Player $player) {

        switch (mt_rand(1, 3)) {
            case 1:
                $player->getInventory()->addItem(Item::get(
                    ItemIds::BONE_BLOCK, 11, 1
                )->setCustomName("§r§l§e< §f뼈 블럭 §e>"));
                break;

            case 2:
                $player->getInventory()->addItem(Item::get(
                    ItemIds::BONE, 12, 1
                )->setCustomName("§r§l§e< §f공룡의 뼈 §l§e>"));
                break;

            case 3:
                $player->getInventory()->addItem(Item::get(
                    ItemIds::BONE, 13, 1
                )->setCustomName("§r§l§e< §f스켈레톤의 뼈 §r§l§e>"));
                break;

            case 4:
                $player->sendMessage("§l§6[!] §r§f채집에 실패하였습니다.");
                break;
        }
    }
}
