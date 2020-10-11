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
use Ad5001\Spherable\schematics\Schematics;


class SpheresGenerator extends Generator {
    	
	
	
	/** @var Level */
	protected $level;
	
	/** @var Plugin */
	protected $plugin;
	
	/** @var Array */
	protected $blockCache;
	
	
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
	
	protected $spheresFiller = 
			[
				[Block::AIR, 0, 40],
				[Block::WATER, 0, 30],
				[Block::LAVA, 0, 30],
			];
	
	public function __construct(array $options = []){}
	
	
	/**
	 * Inits the class for the var
	 * @param		ChunkManager		$level
	 * @param		Random				$random
	 * @return		void
	 */
	public function init(ChunkManager $level, Random $random): void {
		//parent::init($level, $random);
		$this->level = $level;
		$this->random = $random;
		$this->blockCache = [];
				
		//create spawn
		if ($this->level->getChunk(16, 16) == null OR !$this->level->getChunk(16, 16)->isGenerated()) {
			var_dump("STARTED SPAWN GENERATION !!");
			$directory = (dirname(__FILE__,3).DIRECTORY_SEPARATOR.'schematics'.DIRECTORY_SEPARATOR);
			$file = "spawn.schematic";
			$filePath = $directory.$file;
			try {
				$schematic = new Schematics($filePath);
				$schematic->load();
			} catch (\Throwable $error) {
				// Handle error
				var_dump("Problem while loading schematic ($filePath): ".$error);
			}
			finally {
				// $schematic->paste( $this->level, new Vector3(($chunkX * 16)+8-$schematic->getWidth(), 127-$schematic->getHeight(), ($chunkZ * 16)+8-$schematic->getLength() ));
				$blocksToPlace = $schematic->getBlock(new Vector3((16 * 16)-$schematic->getWidth(), Level::Y_MAX-$schematic->getHeight(), (16 * 16)-$schematic->getLength() ));
				$this->blockCache = array_merge($this->blockCache, $blocksToPlace);
			}
			var_dump("FINISHED SPAWN GENERATION !!");
		}
		
		// $this->plugin = $level->getServer()->getPluginManager()->getPlugin("Spherable");
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
		var_dump("GENERATING CHUNK ! ($chunkX - $chunkZ)");
		
        $x1 = $chunkX << 4;
        $z2 = $chunkZ << 4;
		$p=0;
		$np=0;
		$lastFenceColumn = 0;
		
		//Check if there is block in cache to place !
        for ($xx = $x1; $xx < $x1 + 16; $xx ++) {
            for ($zz = $z2; $zz < $z2 + 16; $zz ++) {
                for ($y = 0; $y < 256; $y++) {
                    // if (in_array($xx . ':' . $y . ':' . $zz, $this->blockCache)) {
                    if (isset($this->blockCache[$xx . ':' . $y . ':' . $zz])) {
						$blockCacheToPlace = $this->blockCache[$xx . ':' . $y . ':' . $zz];
						$blockCacheToPlace = explode(':', $blockCacheToPlace);
						$id = $blockCacheToPlace[0];
						$meta = $blockCacheToPlace[1];
							settype($id, "integer");
							settype($meta, "integer");
						$this->level->setBlockIdAt($xx, $y, $zz, $id);
						$this->level->setBlockDataAt($xx, $y, $zz, $meta);
						unset($this->blockCache[$xx . ':' . $y . ':' . $zz]);
						$p++;
                    }
					else $np++;
					
					
					//We can Draw the bridges ?
					if ($y == 120 OR $y == 20 OR $y == 240) {
						//Set Border
						if ($this->positionIsBetweenRadius (new Vector3(0, 0, 0), 600, 601, new Vector3($xx, $y, $zz)) OR $this->positionIsBetweenRadius (new Vector3(0, 0, 0), 609, 610, new Vector3($xx, $y, $zz))) {
							$this->level->setBlockIdAt($xx, $y+1, $zz, 85);
							$this->level->setBlockDataAt($xx, $y+1, $zz, 0);
							$this->level->setBlockIdAt($xx, $y, $zz, 5);
							$this->level->setBlockDataAt($xx, $y, $zz, 1);
							$lastFenceColumn++;
							
							if ($lastFenceColumn == 5) {
									$this->level->setBlockIdAt($xx, $y+2, $zz, 50);
									$this->level->setBlockDataAt($xx, $y+2, $zz, 0);								
							}
							if ($lastFenceColumn >= 10 ) {
								for ($i = 0; $i <= 4; $i++) {
									$this->level->setBlockIdAt($xx, $y+2+$i, $zz, 85);
									$this->level->setBlockDataAt($xx, $y+2+$i, $zz, 0);
								}
								$lastFenceColumn = 0;
							}
							$this->level->setBlockIdAt($xx, $y+7, $zz, 158);
							$this->level->setBlockDataAt($xx, $y+7, $zz, 0);									
						}
						//Set Center
						elseif ($this->positionIsBetweenRadius (new Vector3(0, 0, 0), 601, 609, new Vector3($xx, $y, $zz))) {
							if ($this->random->nextRange(0, 200) != 0) {
								$this->level->setBlockIdAt($xx, $y-1, $zz, 5);
								$this->level->setBlockDataAt($xx, $y-1, $zz, 1);
							}
							if ($this->random->nextRange(0, 200) != 0) {
								$this->level->setBlockIdAt($xx, $y+8, $zz, 157);
								$this->level->setBlockDataAt($xx, $y+8, $zz, 0);
							}								
						}
					}
                }
            }
        }
		var_dump("GENERATING CHUNK ($chunkX - $chunkZ) HAVE FINISHED / CHECK BLOCK TO PLACE (P:$p -- NP:$np)");

		$chunk = $this->level->getChunk($chunkX, $chunkZ);
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
		var_dump("POPULATE CHUNK ! ($chunkX - $chunkZ)");
		
		$options = [];
		$this->random->setSeed(0xdeadbeef ^ ($chunkX << 8) ^ $chunkZ ^ $this->level->getSeed());
		$count = $this->random->nextRange(0, 3);

		for($i = 0; $i <= $count; $i++){
			$y = $this->random->nextRange(17, Level::Y_MAX - 25);
			
			$randomType = $this->random->nextRange(0, 100);
			if ($randomType <= 8) $options["type"] = 'trashedSphere';
			elseif ($randomType <= 40) $options["type"] = 'bubble';
			elseif ($randomType <= 99) $options["type"] = 'sphere';
			elseif ($randomType <= 100) $options["type"] = 'mystic';
			
			$options["underGroundNoise"] = $this->random->nextRange(0, 2) == 0;
			$options["borderNoise"] = $this->random->nextRange(0, 1) == 0;
			$options["dust"] = $this->random->nextRange(120, 500);
			if ($this->random->nextRange(0, 2) == 0) $options["filled"] = $this->selectRandomBlock($this->spheresFiller);
			else $options["filled"] = false;
			 
			if     ($chunkX % 11 == 0 OR $chunkZ % 11 == 0) $maxRadius = $y / 10;
			elseif ($chunkX % 5 == 0 OR $chunkZ % 5 == 0) $maxRadius = $y / 14;
			elseif ($chunkX % 3 == 0 OR $chunkZ % 3 == 0) $maxRadius = $y / 18;
			else    $maxRadius = $y / 16;
			
			if ($maxRadius < 6) $maxRadius = 6;
			// $maxRadius is situated between 6 and 12.8 depending on Y choosen
			// Let's add a little bit more random
			$minradius = $maxRadius / 2;
			if ($minradius < 5) $minradius = 5;
			$radius = $this->random->nextRange((int) round($minradius), (int) round($maxRadius));
			// Generating planet
			$x = $chunkX * 16 + $this->random->nextRange(0, 15);
			$z = $chunkZ * 16 + $this->random->nextRange(0, 15);
			$center = new Vector3($x, $y, $z);
			if ($this->generatePlanet($center, $radius, $options)) {
				// $plugin = $this->getServer()->getPluginManager()->getPlugin("Spherable");
				// $plugin->
			}
			else {
				var_dump("Planet not generated");
			}	
		}
	}

	/**
	 * Returns the dafault spawn
	 *
	 * @return Vector3
	 */
	public function getSpawn() : Vector3{
		return new Vector3(264, 127, 264);
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
		$radiusSquaredDustMin = ($radius + 2) ** 2;
		$radiusSquaredDustMax = ($radius + ($radius/3)) ** 2;
		$GlassColor = $this->random->nextRange(0, 15);
		$canSpawn = True;
		
		//Detect if island do not ovveride another island
		$radiusCanSpawn = $radius + 7; 
		$radiusSquaredCanSpawn = $radiusCanSpawn ** 2;
		for ($x = $center->x - $radiusCanSpawn; $x <= $center->x + $radiusCanSpawn; $x++) {
			$xsquared = ($center->x - $x) * ($center->x - $x);
			for ($y = $center->y - $radiusCanSpawn; $y <= $center->y + $radiusCanSpawn; $y++) {				
				$ysquared = ($center->y - $y) * ($center->y - $y);
				for ($z = $center->z - $radiusCanSpawn; $z <= $center->z + $radiusCanSpawn; $z++) {
					$zsquared = ($center->z - $z) * ($center->z - $z);
					if($xsquared + $ysquared + $zsquared < $radiusSquaredCanSpawn) {
						if ($this->level->getBlockIdAt($x, $y, $z) != 0 || isset($this->blockCache[$x . ':' . $y . ':' . $z])) {
							$canSpawn = False;
							break 3;
						}
					}
				}
			}
		}
		
		if ($canSpawn) {
			
			//Spawn Mystic island
			if ($options["type"] == 'mystic') {
				$this->level->setBlockIdAt($center->x, $center->y, $center->z, 208);
				$this->level->setBlockDataAt($center->x, $center->y, $center->z, 0);
				
				$this->level->setBlockIdAt($center->x+1, $center->y, $center->z, 241);
				$this->level->setBlockDataAt($center->x+1, $center->y, $center->z, $GlassColor);
				$this->level->setBlockIdAt($center->x-1, $center->y, $center->z, 241);
				$this->level->setBlockDataAt($center->x-1, $center->y, $center->z, $GlassColor);
				$this->level->setBlockIdAt($center->x, $center->y, $center->z-1, 241);
				$this->level->setBlockDataAt($center->x, $center->y, $center->z-1, $GlassColor);
				$this->level->setBlockIdAt($center->x, $center->y, $center->z+1, 241);
				$this->level->setBlockDataAt($center->x, $center->y, $center->z+1, $GlassColor);
			}		
			//Spawn an island
			else {
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
							//Check if we are inside Sphere
							if($xsquared + $ysquared + $zsquared < $radiusSquared) {
								
								$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
								
								if ($options["type"] == 'bubble') {
									if ( $y >= $center->y) {
										// #Generate random hole in surface, not on border !
										if ( $y == $center->y && $xsquared + $ysquared + $zsquared < ($radius-1) ** 2 && $this->random->nextRange(0, 15) == 15) {
											//No block, just air
										}
										// #Generation of bubble									
										elseif($y > $center->y) {
											$radiusSquaredBorder = ($radius-1) ** 2;
											if ($xsquared + $ysquared + $zsquared >= $radiusSquaredBorder) {
												//IF BorderNoise and Y+1 (First border of glass)
												if ($options["borderNoise"] && $y == ($center->y + 1) && $radius >= 7) {
													$borderNoise = $this->random->nextRange(0, 10);
													
													if ($borderNoise <= 4) {
														$this->level->setBlockIdAt($x, $y, $z, 241);
														$this->level->setBlockDataAt($x, $y, $z, $GlassColor);
													}										
													elseif ($borderNoise <= 7) {
														$this->level->setBlockIdAt($x, $y, $z, $randomBlock["blockID"]);
														$this->level->setBlockDataAt($x, $y, $z, $randomBlock["blockData"]);
													}
													elseif ($borderNoise <= 9) {
														$this->level->setBlockIdAt($x, $y, $z, $randomBlock["blockID"]);
														$this->level->setBlockDataAt($x, $y, $z, $randomBlock["blockData"]);
														$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
														$this->level->setBlockIdAt($x, $y+1, $z, $randomBlock["blockID"]);
														$this->level->setBlockDataAt($x, $y+1, $z, $randomBlock["blockData"]);
													}
													else {
														$this->level->setBlockIdAt($x, $y, $z, $randomBlock["blockID"]);
														$this->level->setBlockDataAt($x, $y, $z, $randomBlock["blockData"]);
														$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
														$this->level->setBlockIdAt($x, $y+1, $z, $randomBlock["blockID"]);
														$this->level->setBlockDataAt($x, $y+1, $z, $randomBlock["blockData"]);
														$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
														$this->level->setBlockIdAt($x, $y+2, $z, $randomBlock["blockID"]);
														$this->level->setBlockDataAt($x, $y+2, $z, $randomBlock["blockData"]);										
													}
												}
												//#Else no borderNoise (bubble) and no block already set
												elseif ($this->level->getBlockIdAt($x, $y, $z) == 0) {
													$this->level->setBlockIdAt($x, $y, $z, 241);
													$this->level->setBlockDataAt($x, $y, $z, $GlassColor);
												}
											}
											//#Inside bubble, add some noise block on Y+1 if no air under
											elseif ($xsquared + $ysquared + $zsquared <= $radiusSquaredBorder) {
												if ($this->level->getBlockIdAt($x, $y-1, $z) != 0 && ($this->random->nextRange(0, 60) >= 59)) {
													$this->level->setBlockIdAt($x, $y, $z, $randomBlock["blockID"]);
													$this->level->setBlockDataAt($x, $y, $z, $randomBlock["blockData"]);										
													
												}
											}
										}
										//Generate ground
										else {
											$this->level->setBlockIdAt($x, $y, $z, $randomBlock["blockID"]);
											$this->level->setBlockDataAt($x, $y, $z, $randomBlock["blockData"]);																			
										}
									}
									//Is filled with something ? 
									//Apply inside the sphere, not on border
									elseif ($options["filled"] AND $xsquared + $ysquared + $zsquared < ($radius-1.5) ** 2) {
										$this->level->setBlockIdAt($x, $y, $z, $options["filled"]["blockID"]);
										$this->level->setBlockDataAt($x, $y, $z, $options["filled"]["blockData"]);
									}
									//Else, just add block
									else {
										$this->level->setBlockIdAt($x, $y, $z, $randomBlock["blockID"]);
										$this->level->setBlockDataAt($x, $y, $z, $randomBlock["blockData"]);									
									}
								}
								elseif ($options["type"] == 'sphere') {
									//Is filled with something ?
									//Apply inside the sphere, not on border
									if ($options["filled"] AND $xsquared + $ysquared + $zsquared < ($radius-1.5) ** 2) {
										$this->level->setBlockIdAt($x, $y, $z, $options["filled"]["blockID"]);
										$this->level->setBlockDataAt($x, $y, $z, $options["filled"]["blockData"]);
									}
									//Else, just add block
									else {
										$this->level->setBlockIdAt($x, $y, $z, $randomBlock["blockID"]);
										$this->level->setBlockDataAt($x, $y, $z, $randomBlock["blockData"]);									
									}
								}
								elseif ($options["type"] == 'trashedSphere') {
									// if($this->random->nextRange(0, 100) <= (int) round((100 - ( ($xsquared + $ysquared + $zsquared) * 100 / $radiusSquared))/3)) {
									if($this->random->nextRange(0, 200) <= (int) round(100-((sqrt( ($xsquared + $ysquared + $zsquared) * 100 / $radiusSquared))*10))) {
										$this->level->setBlockIdAt($x, $y, $z, $randomBlock["blockID"]);
										$this->level->setBlockDataAt($x, $y, $z, $randomBlock["blockData"]);									
									}
								}
								
								//Underground Noise//
								//If current block is under middle(Y) AND is the last block on the radius
								if ($options["underGroundNoise"] AND ($options["type"] == 'sphere' OR $options["type"] == 'bubble') AND $y < $center->y AND $xsquared + $ysquared + $zsquared > ($radius-1.5) ** 2) {
									//Is there any block at y-3 arround ?
									if ($this->level->getBlockIdAt($x+1, $y-3, $z) != 0 OR $this->level->getBlockIdAt($x-1, $y-3, $z) != 0 OR $this->level->getBlockIdAt($x, $y-3, $z+1) != 0 OR $this->level->getBlockIdAt($x, $y-3, $z-1) != 0) {
										$randThirdBlock = $this->random->nextRange(0, 1);
										if ($randThirdBlock == 0) {
											//Add one block
											$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
											$this->level->setBlockIdAt($x, $y-1, $z, $randomBlock["blockID"]);
											$this->level->setBlockDataAt($x, $y-1, $z, $randomBlock["blockData"]);
										}
										else {
											//Add two block
											$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
											$this->level->setBlockIdAt($x, $y-1, $z, $randomBlock["blockID"]);
											$this->level->setBlockDataAt($x, $y-1, $z, $randomBlock["blockData"]);
											$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
											$this->level->setBlockIdAt($x, $y-2, $z, $randomBlock["blockID"]);
											$this->level->setBlockDataAt($x, $y-2, $z, $randomBlock["blockData"]);
										}
									}
									//elseif Is there any block at y-2 arround ?
									elseif (($this->level->getBlockIdAt($x+1, $y-2, $z) != 0 OR $this->level->getBlockIdAt($x-1, $y-2, $z) != 0 OR $this->level->getBlockIdAt($x, $y-2, $z+1) != 0 OR $this->level->getBlockIdAt($x, $y-2, $z-1) != 0)) {
										$randSecondBlock = $this->random->nextRange(0, 1);
										if ($randSecondBlock == 0) {
											//Add one block
											$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
											$this->level->setBlockIdAt($x, $y-1, $z, $randomBlock["blockID"]);
											$this->level->setBlockDataAt($x, $y-1, $z, $randomBlock["blockData"]);
										}
									}
									else {
										$randElseBlock = $this->random->nextRange(0, 1);
										if ($randElseBlock == 0) {
											//Add one block
											$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
											$this->level->setBlockIdAt($x, $y-1, $z, $randomBlock["blockID"]);
											$this->level->setBlockDataAt($x, $y-1, $z, $randomBlock["blockData"]);
											$randElseBlock = $this->random->nextRange(0, 1);
											if ($randElseBlock == 0) {
												//Add one block
												$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
												$this->level->setBlockIdAt($x, $y-2, $z, $randomBlock["blockID"]);
												$this->level->setBlockDataAt($x, $y-2, $z, $randomBlock["blockData"]);
												$randElseBlock = $this->random->nextRange(0, 1);
												if ($randElseBlock == 0) {
													//Add one block
													$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
													$this->level->setBlockIdAt($x, $y-3, $z, $randomBlock["blockID"]);
													$this->level->setBlockDataAt($x, $y-3, $z, $randomBlock["blockData"]);
												}
											}
										}
									}
								}
							}
							//DUST//
							//Apply dust arround sphere
							elseif ($xsquared + $ysquared + $zsquared > $radiusSquaredDustMin && $xsquared + $ysquared + $zsquared < $radiusSquaredDustMax) {
								if ($options["type"] != 'trashedSphere' AND $this->random->nextRange(0, $options["dust"]) >= $options["dust"]) {
									$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
									$this->level->setBlockIdAt($x, $y-3, $z, $randomBlock["blockID"]);
									$this->level->setBlockDataAt($x, $y-3, $z, $randomBlock["blockData"]);
								}
								elseif ($options["type"] == 'trashedSphere' AND $this->random->nextRange(0, (int) round($options["dust"]/1.5)) >= (int) round($options["dust"]/1.5)) {
									$randomBlock = $this->selectRandomBlock($currentSphereBlocks);
									$this->level->setBlockIdAt($x, $y-3, $z, $randomBlock["blockID"]);
									$this->level->setBlockDataAt($x, $y-3, $z, $randomBlock["blockData"]);
								}
							}
						}
					}
				}
			}
		}
	return $canSpawn;
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
	
	//Ex radius at 3 block  positionIsBetweenRadius(3,3,X)
	Public function positionIsBetweenRadius (Vector3 $radiusCenterPos, $radiusMin, $radiusMax, $posToCheck) : bool {
		$radiusSquaredMin = $radiusMin ** 2;
		$radiusSquaredMax = $radiusMax ** 2;
		$xsquared = ($radiusCenterPos->x - $posToCheck->x) * ($radiusCenterPos->x - $posToCheck->x);
		$ysquared = ($radiusCenterPos->y - $posToCheck->y) * ($radiusCenterPos->y - $posToCheck->y);
		$zsquared = ($radiusCenterPos->z - $posToCheck->z) * ($radiusCenterPos->z - $posToCheck->z);		
		if ($xsquared + $ysquared + $zsquared >= $radiusSquaredMin AND $xsquared + $ysquared + $zsquared < $radiusSquaredMax ) {return true;}
		else {return false;}
	}
}