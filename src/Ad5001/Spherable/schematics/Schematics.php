<?php

declare(strict_types=1);
namespace Ad5001\Spherable\schematics;

use pocketmine\level\ChunkManager;
use pocketmine\level\utils\SubChunkIteratorManager;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\nbt\BigEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;

class Schematics {

    /** @var string */
    protected $file;

    /** @var CompoundTag */
    protected $namedtag;
	

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function load() : void
    {
        $this->namedtag = (new BigEndianNBTStream())->readCompressed(file_get_contents($this->file));
    }

    public function getWidth() : int
    {
        return $this->namedtag->getShort("Width");
    }

    public function getLength() : int
    {
        return $this->namedtag->getShort("Length");
    }

    public function getHeight() : int
    {
        return $this->namedtag->getShort("Height");
    }

    public function paste(ChunkManager $level, Vector3 $relative_pos, bool $replace_pc_blocks = true) : void
    {

        $blockIds = $this->namedtag->getByteArray("Blocks");
        $blockDatas = $this->namedtag->getByteArray("Data");

        $width = $this->getWidth();
        $length = $this->getLength();
        $height = $this->getHeight();

        $relative_pos = $relative_pos->floor();
        $relx = $relative_pos->x;
        $rely = $relative_pos->y;
        $relz = $relative_pos->z;

        $iterator = new SubChunkIteratorManager($level);

        $wl = $width * $length;

        for ($x = 0; $x < $width; ++$x) {
            $xPos = $x + $relx;

            for ($z = 0; $z < $length; ++$z) {
                $zPos = $z + $relz;
                $zwx = $z * $width + $x;
				
					for ($y = 0; $y < $height; ++$y) {
						$index = $y * $wl + $zwx;

						$id = ord($blockIds{$index});
						$damage = ord($blockDatas{$index});

						// if ($replace_pc_blocks && isset(Utils::REPLACEMENTS[$id])) {
							// [$new_id, $new_damage] = Utils::REPLACEMENTS[$id][$damage] ?? Utils::REPLACEMENTS[$id][-1] ?? [$id, $damage];
							// $id = $new_id ?? $id;
							// $damage = $new_damage ?? $damage;
						// }
					
						$yPos = $y + $rely;
						$iterator->moveTo($xPos, $yPos, $zPos);
						$iterator->currentSubChunk->setBlock($xPos & 0x0f, $yPos & 0x0f, $zPos & 0x0f, $id, $damage);
					}
            }
        }

        if ($level instanceof Level) {
			for ($chunkX = $relx >> 4; $chunkX <= ($relx + $width - 1) >> 4; ++$chunkX) {
				for ($chunkZ = $relz >> 4; $chunkZ <= ($relz + $length - 1) >> 4; ++$chunkZ) {
					$level->clearChunkCache($chunkX, $chunkZ);
					foreach ($level->getChunkLoaders($chunkX, $chunkZ) as $loader) {
						$loader->onChunkChanged($level->getChunk($chunkX, $chunkZ));
					}
				}
			}
        }
    }
	
    public function getBlock(Vector3 $relative_pos, bool $replace_pc_blocks = true) : array
    {
		$tmp = array();
        $blockIds = $this->namedtag->getByteArray("Blocks");
        $blockDatas = $this->namedtag->getByteArray("Data");

        $width = $this->getWidth();
        $length = $this->getLength();
        $height = $this->getHeight();

        $relative_pos = $relative_pos->floor();
        $relx = $relative_pos->x;
        $rely = $relative_pos->y;
        $relz = $relative_pos->z;

        $wl = $width * $length;

        for ($x = 0; $x < $width; ++$x) {
            $xPos = $x + $relx;

            for ($z = 0; $z < $length; ++$z) {
                $zPos = $z + $relz;
                $zwx = $z * $width + $x;
				
					for ($y = 0; $y < $height; ++$y) {
						$index = $y * $wl + $zwx;

						$id = ord($blockIds{$index});
						$damage = ord($blockDatas{$index});
					
						$yPos = $y + $rely;
						$tmp[$xPos . ':' . $yPos . ':' . $zPos] = $id . ':' . $damage;
					}
            }
        }
		return $tmp;
    }

}