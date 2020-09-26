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

namespace Ad5001\Spherable\generators\spheres;

use pocketmine\level\generator\Generator;
use pocketmine\level\generator\biome\BiomeSelector;
use pocketmine\level\generator\biome\Biome;
use pocketmine\level\generator\object\OreType;
use pocketmine\level\generator\populator\GroundCover;
use pocketmine\level\generator\populator\Ore;
use pocketmine\level\generator\populator\Populator;
use pocketmine\block\Block;
use pocketmine\level\Position;
try {
	if(!class_exists("pocketmine\\block\\BlockFactory")) {
		class_alias("pocketmine\\block\\Block", "pocketmine\\block\\BlockFactory");
	}
} catch(Throwable $e){
	class_alias("pocketmine\\block\\Block", "pocketmine\\block\\BlockFactory");
}
use pocketmine\level\ChunkManager;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class SpheresGenerator extends Generator {
    	
	
	
	/** @var Level */
	protected $level;
	
	
	/** @var Random */
	protected $random;


	/** 
	 * @var array[]
	 * 
	 * An array of planets made of different blocks.
	 **/
	//[ID,DATA,PercentSize]
	//Array: Low number Index = bottom -- High number = Top of the sky
	//Top of the array = bottom ...
	protected $spheresBlocks = [
		//Bottom world
		[//Bottom
			[
				[Block::DIAMOND_ORE, 0, 30],
				[Block::OBSIDIAN, 0, 70],
			],
			[
				[Block::GOLD_ORE, 0, 05],
				[Block::STONE, 0, 95], 	
			],
			[
				[Block::DIAMOND_ORE, 0, 5],
				[Block::WOOD, 12, 95],
			],
			[
				[Block::DIAMOND_ORE, 0, 5],
				[Block::WOOD, 13, 95],
			],
			[
				[Block::DIAMOND_ORE, 0, 5],
				[Block::WOOD, 14, 95],
			],
			[
				[Block::DIAMOND_ORE, 0, 5],
				[Block::WOOD, 15, 95],
			],
			[
				[Block::DIAMOND_ORE, 0, 5],
				[Block::WOOD2, 12, 95],
			],
			[
				[Block::DIAMOND_ORE, 0, 5],
				[Block::WOOD2, 13, 95],
			],		
			[
				[Block::DIAMOND_ORE, 0, 3],
				[Block::SNOW_BLOCK, 0, 97],
			],	
			[
				[Block::DIAMOND_ORE, 0, 5],
				[Block::COBWEB, 0, 95],
			],
			[
				[Block::DIAMOND_ORE, 0, 5],
				[Block::WOOL, 0, 95],
			],
			[
				[Block::DIAMOND_ORE, 0, 5],
				[Block::WOOL, 1, 95],
			],
			[
				[Block::DIAMOND_ORE, 0, 5],
				[Block::WOOL, 3, 95],
			],
			[
				[Block::DIAMOND_ORE, 0, 5],
				[Block::WOOL, 4, 95],
			],
			[
				[Block::DIAMOND_ORE, 0, 5],
				[Block::WOOL, 0, 95],
			],	
			[
				[Block::DIAMOND_ORE, 0, 7],
				[Block::PACKED_ICE, 0, 93],
			],
			[
				[Block::DIAMOND_ORE, 0, 3],
				[Block::SLIME_BLOCK, 0, 97],
			],
			[
				[Block::DIAMOND_ORE, 0, 5],
				[Block::QUARTZ_BLOCK, 0, 95],
			],
			[
				[Block::DIAMOND_ORE, 0, 2],
				[Block::NETHERRACK, 0, 98],
			],
			[
				[Block::DIAMOND_ORE, 0, 5],
				[Block::EMERALD_ORE, 0, 95],
			],
			[
				[Block::DIAMOND_ORE, 0, 7],
				[Block::REDSTONE_LAMP, 0, 93],
			],
			[
				[Block::DIAMOND_ORE, 0, 10],
				[Block::END_STONE, 0, 90],
			],
			[
				[Block::DIAMOND_ORE, 0, 10],
				[Block::NETHER_BRICK_BLOCK, 0, 90],
			],
			[
				[Block::DIAMOND_ORE, 0, 10],
				[Block::MELON_BLOCK, 0, 90],
			],
			[
				[Block::DIAMOND_ORE, 0, 7],
				[Block::GLOWSTONE, 0, 93],
			],
			[
				[Block::DIAMOND_ORE, 0, 7],
				[Block::PUMPKIN, 0, 93],
			],
			[
				[Block::DIAMOND_ORE, 0, 10],
				[Block::SOUL_SAND, 0, 90],
			],
			[
				[Block::DIAMOND_ORE, 0, 10],
				[Block::SPONGE, 0, 90],
			],
			[
				[Block::DIAMOND_ORE, 0, 10],
				[Block::PRISMARINE, 0, 90],
			],
			[
				[Block::DIAMOND_ORE, 0, 10],
				[Block::SEA_LANTERN, 0, 90],
			],
			[
				[Block::DIAMOND_ORE, 0, 10],
				[Block::NETHER_REACTOR, 0, 90],
			],			
		],
		[//CENTER
			[
				[Block::REDSTONE_ORE, 0, 10],
				[Block::STONE, 0, 90], 

			],
			[
				[Block::GOLD_ORE, 0, 7],
				[Block::BOOKSHELF, 0, 93],
			],
			[
				[Block::DIAMOND_ORE, 0, 7],
				[Block::LAPIS_BLOCK, 0, 93],
			],
			[
				[Block::DIAMOND_ORE, 0, 5],
				[Block::REDSTONE_BLOCK, 0, 95],
			],

			[
				[Block::IRON_ORE, 0, 5],
				[Block::PLANKS, 0, 95],
			],
			[
				[Block::COAL_ORE, 0, 5],
				[Block::LEAVES, 4, 95],
			],
			[
				[Block::NOTEBLOCK, 0, 100],
			],
			[
				[Block::COBWEB, 0, 100],
			],
			[
				[Block::COAL_ORE, 0, 10],
				[Block::STONE_BRICK, 0, 90],
			],
			[
				[Block::COAL_ORE, 0, 7],
				[Block::GRAVEL, 0, 43],
				[Block::STONE, 0, 50],
			],
			[
				[Block::IRON_ORE, 0, 7],
				[Block::SAND, 0, 40],
				[Block::SANDSTONE, 0, 53],
			],
		],
		[//TOP
			[
				[Block::IRON_ORE, 0, 10],
				[Block::GRASS, 0, 90], 

			],
			[
				[Block::GOLD_ORE, 0, 5],
				[Block::DIRT, 0, 95], 

			],
			[
				[Block::COAL_ORE, 0, 15],
				[Block::STONE, 0, 85], 

			],
			[
				[Block::IRON_ORE, 0, 10],
				[Block::COAL_ORE, 0, 10],
				[Block::SNOW_BLOCK, 0, 80],
			],
			[
				[Block::SNOW_BLOCK, 0, 100],
			],
			[
				[Block::COBWEB, 0, 100],
			],
			[
				[Block::IRON_ORE, 0, 5],
				[Block::WOOL, 0, 95],
			],
			[
				[Block::COAL_ORE, 0, 5],
				[Block::WOOL, 1, 95],
			],
			[
				[Block::GOLD_ORE, 0, 5],
				[Block::COAL_ORE, 0, 5],
				[Block::WOOL, 3, 90],
			],
			[
				[Block::GOLD_ORE, 0, 5],
				[Block::WOOL, 4, 95],
			],
			[
				[Block::STONE, 0, 5],
				[Block::WOOL, 0, 95],
			],
			[
				[Block::STONE_BRICK, 0, 100],
			],
			[
				[Block::GRAVEL, 0, 40],
				[Block::STONE, 0, 60],
			],
			[
				[Block::SAND, 0, 47],
				[Block::SANDSTONE, 0, 53],
			],
			[
				[Block::GOLD_ORE, 0, 3],
				[Block::PACKED_ICE, 0, 97],
			],

		],
	];
	
	public function __construct(array $options = []){}
	
	
	/**
	 * Inits the class for the var
	 * @param		ChunkManager		$level
	 * @param		Random				$random
	 * @return		void
	 */
	public function init(ChunkManager $level, Random $random): void {
		$this->level = $level;
		$this->random = $random;
		foreach ($this->spheresBlocks as $sphereLvl) {
			foreach ($sphereLvl as $sphereBlockList) {
				$chanceCount = 0;
				$BlocksName = "";
				foreach ($sphereBlockList as $sphere) {
					$chanceCount += $sphere[2];
					$BlocksName .= $sphere[0]." ";
				}
				if ($chanceCount != 100) var_dump("Problems with :".trim($BlocksName)." - Because chance is: ".$chanceCount);
			}
		}
		
	}
	
	
	
	
	/***
	 * Returns the name of the generator
	 *
	 * @return string
	 */
	public function getName() : string{
		return "spheres";
	}
	
	
	/**
	 * Returns the settings of the generator
	 *
	 * @return array
	 */
	public function getSettings() : array{
		return [];
	}
	
	
	/**
	* Generates a chunk
	 *
	 * @param int $chunkX
	 * @param int $chunkZ
	 * @return void
	 */
	public function generateChunk(int $chunkX, int $chunkZ): void{
		// Leave blank, planets will be generated later
		$chunk = $this->level->getChunk($chunkX, $chunkZ);
		for($x = 0; $x < 16; $x++) {
			for($z = 0; $z < 16; $z++) {
				$chunk->setBiomeId($x, $z, 1);
				if($chunkX == 16 && $chunkZ == 16) $chunk->setBlockId($x, 254, $z, 2);
			}
		}
		$chunk->setGenerated();
	}
	
	
	/**
	* Populates the chunk with planets
	 *
	 * @param int $chunkX
	 * @param int $chunkZ
	 * @return void
	 */
	public function populateChunk(int $chunkX, int $chunkZ): void{
		$options = [];
		$this->random->setSeed(0xdeadbeef ^ ($chunkX << 8) ^ $chunkZ ^ $this->level->getSeed());
		$chunk = $this->level->getChunk($chunkX, $chunkZ);
		$count = $this->random->nextRange(1, 4);
		$options["isFlat"] = $this->random->nextRange(0, 2) == 0;
		$options["underGroundNoise"] = $this->random->nextRange(0, 2) == 0;
		$options["borderNoise"] = $this->random->nextRange(0, 1) == 0;

		for($i = 0; $i <= $count; $i++){
			$y = $this->random->nextRange(17, Level::Y_MAX - 25);
			$maxRadius = $y / 16;
			if($maxRadius < 6) $maxRadius = 6;
			// $maxRadius is situated between 6 and 12.8 depending on Y choosen
			// Let's add a little bit more random
			$radius = $this->random->nextRange(5, (int) round($maxRadius));
			// Generating planet
			$x = $chunkX * 16 + $this->random->nextRange(0, 15);
			$z = $chunkZ * 16 + $this->random->nextRange(0, 15);
			$center = new Vector3($x, $y, $z);
			$this->generatePlanet($center, $radius, $options);
		}
	}

	/**
	 * Returns the dafault spawn
	 *
	 * @return void
	 */
	public function getSpawn() : Vector3{
		return new Vector3(264, 255, 264);
	}

	/**
	 * Generates a planet 
	 * psmcoreactplugin createlevel4psm Welp spheres 9247603569486
	 *
	 * @param Vector3 $center
	 * @param int $radius
	 * @return void
	 */
	public function generatePlanet(Vector3 $center, int $radius, $options){
		$radiusSquared = $radius ** 2;
		
		$canSpawn = True;
		//Detect if island do not ovveride another island
		for ($x = $center->x - $radius; $x <= $center->x + $radius; $x++) {
			$xsquared = ($center->x - $x) * ($center->x - $x);
			for ($y = $center->y - $radius; $y <= $center->y + $radius; $y++) {				
				$ysquared = ($center->y - $y) * ($center->y - $y);
				for ($z = $center->z - $radius; $z <= $center->z + $radius; $z++) {
					$zsquared = ($center->z - $z) * ($center->z - $z);
					if($xsquared + $ysquared + $zsquared < $radiusSquared) {
						if ($this->level->getBlockIdAt($x, $y, $z) != 0) {
							$canSpawn = False;
							break 3;
						}
					}
				}
			}
		}		
		
		$GlassColor = $this->random->nextRange(0, 15);
		if ($canSpawn) {
			$nbLevelBlock = count(array_keys($this->spheresBlocks));
			$perFloorY = round(254/$nbLevelBlock);
			$sphereFloor = floor($center->y/$perFloorY);
			$currentSphereBlocks = $this->spheresBlocks[$sphereFloor][array_rand($this->spheresBlocks[$sphereFloor])];
			for ($x = $center->x - $radius; $x <= $center->x + $radius; $x++) {
				$xsquared = ($center->x - $x) * ($center->x - $x);
				for ($y = $center->y - $radius; $y <= $center->y + $radius; $y++) {				
					$ysquared = ($center->y - $y) * ($center->y - $y);
					for ($z = $center->z - $radius; $z <= $center->z + $radius; $z++) {
						$zsquared = ($center->z - $z) * ($center->z - $z);
						if($xsquared + $ysquared + $zsquared < $radiusSquared) {
							// FLAT ISLAND //
							// #Generate random hole in surface
							if ($options["isFlat"] && $y == $center->y && $xsquared + $ysquared + $zsquared < ($radius-1) ** 2) {
								if ($this->random->nextRange(0, 15) == 15) continue; 
							}
							// #Generation of bubble
							if ($options["isFlat"] && $y > $center->y) {
								$radiusSquaredBorder = ($radius-1) ** 2;
								//TODO: Find why second condition is needed for top block not set
								if ($xsquared + $ysquared + $zsquared > $radiusSquaredBorder OR $y == $center->y + $radius - 1) {
									//IF BorderNoise and Y+1 (First border of glass)
									if ($options["borderNoise"] && $y == ($center->y + 1) && $radius >= 7) {
										$borderNoise = $this->random->nextRange(0, 10);
										
										if ($borderNoise <= 4) {
											$this->level->setBlockIdAt($x, $y, $z, 241, false, false);
											$this->level->setBlockDataAt($x, $y, $z, $GlassColor, false, false);
										}										
										elseif ($borderNoise <= 7) {
											$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
											$this->level->setBlockIdAt($x, $y, $z, $randomBlock["blockID"], false, false);
											$this->level->setBlockDataAt($x, $y, $z, $randomBlock["blockData"], false, false);
											
										}
										elseif ($borderNoise <= 9) {
											$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
											$this->level->setBlockIdAt($x, $y, $z, $randomBlock["blockID"], false, false);
											$this->level->setBlockDataAt($x, $y, $z, $randomBlock["blockData"], false, false);
											$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
											$this->level->setBlockIdAt($x, $y+1, $z, $randomBlock["blockID"], false, false);
											$this->level->setBlockDataAt($x, $y+1, $z, $randomBlock["blockData"], false, false);
										}
										else {
											$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
											$this->level->setBlockIdAt($x, $y, $z, $randomBlock["blockID"], false, false);
											$this->level->setBlockDataAt($x, $y, $z, $randomBlock["blockData"], false, false);
											$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
											$this->level->setBlockIdAt($x, $y+1, $z, $randomBlock["blockID"], false, false);
											$this->level->setBlockDataAt($x, $y+1, $z, $randomBlock["blockData"], false, false);
											$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
											$this->level->setBlockIdAt($x, $y+2, $z, $randomBlock["blockID"], false, false);
											$this->level->setBlockDataAt($x, $y+2, $z, $randomBlock["blockData"], false, false);										
										}
									}
									//#Else no borderNoise (bubble) and no block already set
									elseif ($this->level->getBlockIdAt($x, $y, $z) == 0) {
										$this->level->setBlockIdAt($x, $y, $z, 241, false, false);
										$this->level->setBlockDataAt($x, $y, $z, $GlassColor, false, false);
									}
								}
								//#Inside bubble, add some noise block on Y+1 if no air under
								elseif ($xsquared + $ysquared + $zsquared < $radiusSquaredBorder && $y == ($center->y + 1)) {
									if ($this->level->getBlockIdAt($x, $y-1, $z) != 0 && ($this->random->nextRange(0, 60) >= 59)) {
										$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
										$this->level->setBlockIdAt($x, $y, $z, $randomBlock["blockID"], false, false);
										$this->level->setBlockDataAt($x, $y, $z, $randomBlock["blockData"], false, false);										
										
									}
								}
							continue;
							}
							
							// SPHERE ISLAND //
							// Choosing a random block to place
							$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
							$this->level->setBlockIdAt($x, $y, $z, $randomBlock["blockID"], false, false);
							$this->level->setBlockDataAt($x, $y, $z, $randomBlock["blockData"], false, false);
							
							
						
							//ADD Underground Noise
							//If current block is under middle(Y) AND is the last block on the radius
							//TODO: Find why second condition is needed for top block not set
							if ($options["underGroundNoise"] AND $y < $center->y AND ($xsquared + $ysquared + $zsquared > ($radius-1) ** 2 OR $y == $center->y - $radius - 1)) {
								//Is there any block at y-3 arround ?
								if ($this->level->getBlockIdAt($x+1, $y-3, $z) != 0 OR $this->level->getBlockIdAt($x-1, $y-3, $z) != 0 OR $this->level->getBlockIdAt($x, $y-3, $z+1) != 0 OR $this->level->getBlockIdAt($x, $y-3, $z-1) != 0) {
									$randThirdBlock = $this->random->nextRange(0, 1);
									if ($randThirdBlock == 0) {
										//Add one block
										$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
										$this->level->setBlockIdAt($x, $y-1, $z, $randomBlock["blockID"], false, false);
										$this->level->setBlockDataAt($x, $y-1, $z, $randomBlock["blockData"], false, false);
									}
									else {
										//Add two block
										$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
										$this->level->setBlockIdAt($x, $y-1, $z, $randomBlock["blockID"], false, false);
										$this->level->setBlockDataAt($x, $y-1, $z, $randomBlock["blockData"], false, false);
										$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
										$this->level->setBlockIdAt($x, $y-2, $z, $randomBlock["blockID"], false, false);
										$this->level->setBlockDataAt($x, $y-2, $z, $randomBlock["blockData"], false, false);
									}
								}
								//elseif Is there any block at y-2 arround ?
								elseif (($this->level->getBlockIdAt($x+1, $y-2, $z) != 0 OR $this->level->getBlockIdAt($x-1, $y-2, $z) != 0 OR $this->level->getBlockIdAt($x, $y-2, $z+1) != 0 OR $this->level->getBlockIdAt($x, $y-2, $z-1) != 0)) {
									$randSecondBlock = $this->random->nextRange(0, 1);
									if ($randSecondBlock == 0) {
										//Add one block
										$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
										$this->level->setBlockIdAt($x, $y-1, $z, $randomBlock["blockID"], false, false);
										$this->level->setBlockDataAt($x, $y-1, $z, $randomBlock["blockData"], false, false);
									}
								}
								else {
									$randElseBlock = $this->random->nextRange(0, 1);
									if ($randElseBlock == 0) {
										//Add one block
										$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
										$this->level->setBlockIdAt($x, $y-1, $z, $randomBlock["blockID"], false, false);
										$this->level->setBlockDataAt($x, $y-1, $z, $randomBlock["blockData"], false, false);
										$randElseBlock = $this->random->nextRange(0, 1);
										if ($randElseBlock == 0) {
											//Add one block
											$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
											$this->level->setBlockIdAt($x, $y-2, $z, $randomBlock["blockID"], false, false);
											$this->level->setBlockDataAt($x, $y-2, $z, $randomBlock["blockData"], false, false);
											$randElseBlock = $this->random->nextRange(0, 1);
											if ($randElseBlock == 0) {
												//Add one block
												$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
												$this->level->setBlockIdAt($x, $y-3, $z, $randomBlock["blockID"], false, false);
												$this->level->setBlockDataAt($x, $y-3, $z, $randomBlock["blockData"], false, false);
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}

	Public function selectRandomBlock ($currentSphereBlocks) {
		$rand = $this->random->nextBoundedInt(100) + 1;
		$previousRand = 0;
		foreach($currentSphereBlocks as $block){
			$blockChance = $block[2];
			$blockData = $block[1];
			$blockID = $block[0];
			$rand -= $previousRand;
			if($rand <= $blockChance) {
				$tmp = [];
				$tmp["blockID"] = $blockID;
				$tmp["blockData"] = $blockData;
				return $tmp;
			}
			else $previousRand = $blockChance;
		}
		
		$tmp["blockID"] = 0;
		$tmp["blockData"] = 0;
		return $tmp;	
	}
}