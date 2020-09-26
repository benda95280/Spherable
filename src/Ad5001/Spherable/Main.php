<?php

/***
*      ____          _                        _      _       
 *     / ___|  _ __  | |__    ___  _ __  __ _ | |__  | |  ___ 
 *     \___ \ | '_ \ | '_ \  / _ \| '__|/ _` || '_ \ | | / _ \
 *      ___) || |_) || | | ||  __/| |  | (_| || |_) || ||  __/
 *     |____/ | .__/ |_| |_| \___||_|   \__,_||_.__/ |_| \___|
 *            |_|                                             
 * 
 * Spheres world generator. A new survival challenge.
 * @author Ad5001 <mail@ad5001.eu>
 * @copyright (C) 2017 Ad5001
 * @license NTOSL (View LICENSE.md)
 * @package Spherical
 * @version 1.0.0
 * @link https://download.ad5001.eu/en/view.php?name=Spherable&src=github
 */
declare(strict_types = 1);

namespace Ad5001\Spherable;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\level\generator\Generator;
use pocketmine\level\generator\GeneratorManager;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\level\Position;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\entity\Effect;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;
use pocketmine\block\Block;
use pocketmine\entity\projectile\Arrow;




use Ad5001\Spherable\generators\spheres\SpheresGenerator;
use Ad5001\Spherable\commands\sphgenCommand;



class Main extends PluginBase implements Listener{

    public $playersResist = [];
	public $windTotalTick = 0;
	public $windCurrentTick = 0;
	public $windPauseTick = 0;
	public $windBaseX = 0;
	public $windBaseZ = 0;
	
    /** @var Main */
    private static $instance;
	
	/**
	 * When the plugin enables
	 *
	 * @return void
	 */
	public function onEnable(){
		
		if (self::$instance === null) {
			self::$instance = $this;
		}
		
		GeneratorManager::addGenerator(SpheresGenerator::class, "spheres");
		$this->getServer()->getCommandMap()->register("sphgen", new sphgenCommand($this));
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		
		//Create task for winds
		$this->getScheduler()->scheduleRepeatingTask(new class extends \pocketmine\scheduler\Task{
			public function onRun(int $currentTick) : void{
				if (Main::getInstance()->windPauseTick < 0) {
					if(Main::getInstance()->windCurrentTick < Main::getInstance()->windTotalTick) {
						if (Main::getInstance()->windCurrentTick <= (Main::getInstance()->windTotalTick/2)) {
							//Increase
							$baseValue = Main::getInstance()->windCurrentTick/(Main::getInstance()->windTotalTick/2);
						}
						else  {
							$baseValue = 1-((Main::getInstance()->windCurrentTick-(Main::getInstance()->windTotalTick/2))/(Main::getInstance()->windTotalTick/2));
							//decrease
						}
						//Apply to players
						foreach(Main::getInstance()->getServer()->getOnlinePlayers() as $player) {
							if ($player->getLevel()->getProvider()->getGenerator() == "spheres" ) {
								$playerMotion = $player->getMotion();
								
								if (!$player->onGround) {
									$playerMotion->y -= 0.3;
								}
								
								$mot = new Vector3($playerMotion->x + (Main::getInstance()->windBaseX*$baseValue), $playerMotion->y, $playerMotion->z+(Main::getInstance()->windBaseZ*$baseValue));
								$player->setMotion($mot);
							}
						}						
						Main::getInstance()->windCurrentTick++;
					}
					else {
						$random = new Random;
						//set new random total tick (30-60) = Duration
						Main::getInstance()->windTotalTick = $random->nextRange(30, 60); //rand (30, 80)
						
						//reset current tick to zero
						Main::getInstance()->windCurrentTick = 0;
						
						//Set a random pause (150-400)
						Main::getInstance()->windPauseTick = $random->nextRange(1000, 3000); //rand ();
						
						//Set new value for next winds
						Main::getInstance()->windBaseX = ($random->nextRange(10, 20))/100; //(rand (10, 20))/100;
						Main::getInstance()->windBaseZ = ($random->nextRange(10, 20))/100; //(rand (10, 20))/100;
						//Negative or positive ?
						if ($random->nextBoolean()) Main::getInstance()->windBaseX = (Main::getInstance()->windBaseX * -1);
						if ($random->nextBoolean()) Main::getInstance()->windBaseZ = (Main::getInstance()->windBaseZ * -1);
						
					}
				}
				else {
					Main::getInstance()->windPauseTick--;
				}
			}
		}, 1); //period/interval
	}
	
    public static function getInstance(): Main
    {
        return self::$instance;
    }
	
	/**
	 */
	public function onEntityLevelChange(\pocketmine\event\entity\EntityLevelChangeEvent $event){
		if($event->getTarget()->getProvider()->getGenerator() == "spheres" && $event->getEntity() instanceof Player){
			$event->getEntity()->setSpawn(new Position(264, 255, 264, $event->getTarget()));
		}
	}
	
	
    public function onPlayerMove(PlayerMoveEvent $event): void
    {
		// public $windTotalTick = 0;
		// public $windCurrentTick = 0;
		// public $windPauseTick = 0;

		$player = $event->getPlayer();
		if($player->getLevel()->getProvider()->getGenerator() == "spheres"){

		}
	}

	/**
	 */
	//TODO: Find block hitted by arrow to explode the bubble
	public function OnProjectileHitBlockEvent(\pocketmine\event\entity\ProjectileHitBlockEvent $event){
        if($event->getEntity()->getLevel()->getProvider()->getGenerator() == "spheres") {
			$arrow = $event->getEntity();
			if($arrow instanceof Arrow) {
				$block = $event->getBlockHit();
				if($block->getId() == 241){
					Main::getInstance()->breakBubble($block);
					$arrow->flagForDespawn();
				}					
			}
		}
	}
	
	
	/**
	 */
	public function onBlockBreak(\pocketmine\event\block\BlockBreakEvent $event){
        if($event->getBlock()->getLevel()->getProvider()->getGenerator() == "spheres"){
            if($event->getBlock()->getId() == 241){
				Main::getInstance()->breakBubble($event->getBlock());
            }
		}
	}
	
	 public function breakBubble(Block $block): void {
		 $level = $block->getLevel();
		 $listBlock = [];
		 $checkedBlock = [];
		 $scanned = 0;
		 Main::getInstance()->findBubble($block,$scanned,$listBlock,$checkedBlock);
		 var_dump ($scanned);
		 foreach ($listBlock as $blockInList) {
			 $pos = explode(':', $blockInList);
			 $level->setBlockIdAt(intval($pos[0]), intval($pos[1]), intval($pos[2]), Block::AIR);
			 $level->setBlockDataAt(intval($pos[0]), intval($pos[1]), intval($pos[2]), 0);
		 }
	 }
	
    public function findBubble(Block $block, int &$scanned, array &$listBlock, array &$checkedBlock): void
    {
		$level = $block->getLevel();
		//Check if scanned block is connected to others (-y / Y / +Y)
		foreach ([-1,0,1] as $i) {		
			//Do i have already scanned that block ?
			$checkBlock = $level->getBlockAt($block->getX(), $block->getY()+$i, $block->getZ());
			if (!in_array($checkBlock->getX() . ':' . $checkBlock->getY() . ':' . $checkBlock->getZ(),$checkedBlock)) 
			{
				//Add to break list that block
				if ($checkBlock->getId() == 241 ) {
					array_push($listBlock, $checkBlock->getX() . ':' . $checkBlock->getY() . ':' . $checkBlock->getZ());
				}
				//Add to the list of already checked block
				array_push($checkedBlock, $checkBlock->getX() . ':' . $checkBlock->getY() . ':' . $checkBlock->getZ());
				
                //Check if scanned block is connected to others (Horizontal SCAN)
				foreach ([
					$checkBlock->getSide(2),	$checkBlock->getSide(3),	$checkBlock->getSide(4),	$checkBlock->getSide(5),
					$checkBlock->getSide(4)->getSide(3), $checkBlock->getSide(4)->getSide(2), $checkBlock->getSide(5)->getSide(3), $checkBlock->getSide(2)->getSide(2)
					] as $b) {
						$scanned++;
						//Avoid too much scan
						if ($scanned > 250000) {
							break;
						}
						//If one side is bubble, scan it !
						if ($b->getId() == 241 ) {
							Main::getInstance()->findBubble($b, $scanned, $listBlock, $checkedBlock);
						}
				}
			}
		}
    }

	
	
	/**
	 */
	public function onJoin(\pocketmine\event\player\PlayerJoinEvent $event){
		if($event->getPlayer()->getLevel()->getProvider()->getGenerator() == "spheres"){
			$event->getPlayer()->setSpawn(new Position(264, 255, 264, $event->getPlayer()->getLevel()));
		}
	}
	
	public function onBlockPlace(BlockPlaceEvent $event)
	{
		$blockId = $event->getBlock()->getId();
		if ($blockId === 241) {
			$event->setCancelled(true);
		}
	}
}
