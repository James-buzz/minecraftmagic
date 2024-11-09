<?php

namespace Database\Seeders;

use App\Models\ArtStyle;
use App\Models\ArtType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArtSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seededData = [
            [
                'name' => 'server_logo',
                'description' => 'Minecraft server logo designs with various themes',
                'styles' => [
                    [
                        'name' => 'Arctic Expedition',
                        'description' => 'A chilly adventure in the frozen north',
                        'prompt' => 'Design a Minecraft Server Logo.\nThe server is named: <server_name>.\nThe logo should feature icy, blue letters with a polar bear and igloo in the background.\nUse stark whites and icy blues, creating a cold arctic scene.',
                        'resource_path' => '/assets/art/server_logo/arctic_expedition.png',
                    ],
                    [
                        'name' => 'Dragon\'s Lair',
                        'description' => 'Face fearsome dragons in their den',
                        'prompt' => 'Design a Minecraft Server Logo.\nThe server is named: <server_name>.\nThe logo should feature fiery letters with a dragon\'s claw and flames in the background.\nUse fiery reds and oranges, set against a cave-like dark background.',
                        'resource_path' => '/assets/art/server_logo/dragons_lair.png',
                    ],
                    [
                        'name' => 'End Explorer',
                        'description' => 'Journey through the mysterious End dimension',
                        'prompt' => 'Design a Minecraft Server Logo.\nThe server is named: <server_name>.\nThe logo should have surreal, purple-tinged letters with an Ender Dragon silhouette in the background.\nUse dark purples and blacks to evoke the mysterious End dimension.',
                        'resource_path' => '/assets/art/server_logo/end_explorer.png',
                    ],
                    [
                        'name' => 'Haunted Hallows',
                        'description' => 'Spooky adventures in a ghostly realm',
                        'prompt' => 'Design a Minecraft Server Logo.\nThe server is named: <server_name>.\nThe logo should have eerie, ghostly letters with a haunted mansion and bare trees in the background.\nUse muted grays and blues to convey a spooky, atmospheric environment.',
                        'resource_path' => '/assets/art/server_logo/haunted_hallows.png',
                    ],
                    [
                        'name' => 'Magical Chest',
                        'description' => 'Discover enchanted treasures and artifacts',
                        'prompt' => 'Design a Minecraft Server Logo.\nThe server is named: <server_name>.\nThe logo should have enchanting, sparkling letters with an open, glowing treasure chest in the background.\nUse vibrant colors and mystical glows to emphasize a sense of wonder.',
                        'resource_path' => '/assets/art/server_logo/magical_chest.png',
                    ],
                    [
                        'name' => 'Redstone Engineers',
                        'description' => 'Build complex machines with redstone',
                        'prompt' => 'Design a Minecraft Server Logo.\nThe server is named: <server_name>.\nThe logo should feature mechanical, circuit-like letters with a redstone torch and gears in the background.\nUse bold reds and metallic greys, emphasizing technical and innovative themes.',
                        'resource_path' => '/assets/art/server_logo/redstone_engineers.png',
                    ],
                    [
                        'name' => 'Battle Factions',
                        'description' => 'Join epic battles between rival groups',
                        'prompt' => 'Design a Minecraft Server Logo.\nThe server is named: <server_name>.\nThe logo should feature bold, warlike letters with crossed axes and a war banner behind the text.\nUse dark, foreboding colors and a backdrop of a stormy battlefield.',
                        'resource_path' => '/assets/art/server_logo/battle_factions.png',
                    ],
                    [
                        'name' => 'Dwarven Kingdom',
                        'description' => 'Explore vast underground cities and mines',
                        'prompt' => 'Design a Minecraft Server Logo.\nThe server is named: <server_name>.\nThe logo should have stout, stone-like letters with a dwarf helmet and crossed pickaxes in the background.\nUse earthy tones like browns and grays, set against a backdrop of underground tunnels and caverns.',
                        'resource_path' => '/assets/art/server_logo/dwarven_kingdom.png',
                    ],
                    [
                        'name' => 'Frozen Peaks',
                        'description' => 'Climb treacherous icy mountains',
                        'prompt' => 'Design a Minecraft Server Logo.\nThe server is named: <server_name>.\nThe logo should feature icy, crystal-like letters with a snowy mountain and ski tracks in the background.\nUse cool blues and whites, creating a chilly, wintry scene.',
                        'resource_path' => '/assets/art/server_logo/frozen_peaks.png',
                    ],
                    [
                        'name' => 'Jungle Expedition',
                        'description' => 'Traverse dense, mysterious jungles',
                        'prompt' => 'Design a Minecraft Server Logo.\nThe server is named: <server_name>.\nThe logo should have lush, green letters with a jungle vine and parrot in the background.\nUse vibrant greens and a backdrop of dense, tropical foliage.',
                        'resource_path' => '/assets/art/server_logo/jungle_expedition.png',
                    ],
                    [
                        'name' => 'Pirate Cove',
                        'description' => 'Set sail for swashbuckling adventures',
                        'prompt' => 'Design a Minecraft Server Logo.\nThe server is named: <server_name>.\nThe logo should have adventurous, pirate-themed letters with a ship\'s wheel and anchor in the background.\nUse ocean blues and sandy browns, with a backdrop of a tropical island.',
                        'resource_path' => '/assets/art/server_logo/pirate_cove.png',
                    ],
                    [
                        'name' => 'Vanilla Survival',
                        'description' => 'Classic Minecraft survival experience',
                        'prompt' => 'Design a Minecraft Server Logo.\nThe server is named: <server_name>.\nThe logo should feature rugged, earth-toned letters with a simple wooden pickaxe and torch behind the text.\nUse natural colors and a backdrop of an untamed wilderness.',
                        'resource_path' => '/assets/art/server_logo/vanilla_survival.png',
                    ],
                ],
            ],
        ];

        foreach ($seededData as $artType) {
            $type = ArtType::create([
                'name' => $artType['name'],
                'description' => $artType['description'],
            ]);

            foreach ($artType['styles'] as $style) {
                ArtStyle::create([
                    'art_type_id' => $type->id,
                    'name' => $style['name'],
                    'description' => $style['description'],
                    'prompt' => $style['prompt'],
                    'resource_path' => $style['resource_path'],
                ]);
            }
        }
    }
}
