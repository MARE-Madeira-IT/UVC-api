<?php

namespace Database\Seeders;


use App\Models\Density;
use App\Models\Depth;
use App\Models\SurveyProgramFunction;
use App\Models\Indicator;
use App\Models\IndicatorHasValue;
use App\Models\Locality;
use App\Models\Permission;
use App\Models\SurveyProgramHasUser;
use App\Models\Report;
use App\Models\Site;
use App\Models\SizeCategory;
use App\Models\Substrate;
use App\Models\SurveyProgram;
use App\Models\Taxa;
use App\Models\TaxaCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class MareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $surveyProgram = SurveyProgram::create([
            "name" => "MARE-Madeira",
            "description" => "Lorem ipsum dolor sit amet consectetur adipiscing elit Ut et massa mi. Aliquam in hendrerit urna. Pellentesque sit amet sapien.",
            "community_size" => 10,
        ]);

        $permissions = [
            "create",
            "show",
            "edit",
            "delete",
            "admin",
        ];

        foreach ($permissions as $name) {
            Permission::create(["name" => $name]);
        }


        $mareSurveyProgramHasUser = SurveyProgramHasUser::create([
            "survey_program_id" => $surveyProgram->id,
            "user_id" => User::where('note', 'admin account')->first()->id,
            'active' => 1
        ]);

        $mareSurveyProgramHasUser->permissions()->attach(Permission::all()->pluck('id')->toArray());


        $indicators = [
            [
                'name' => 'trophic guild',
                'survey_program_id' => $surveyProgram->id,
                'type' => 'select',
                'values' => [
                    "Omnivore",
                    "Macro-carnivore",
                    "Piscivore",
                    "Invertivore",
                    "Herbivore",
                    "Grazer",
                    "Detritivore",
                    "Planktivore",
                    "Scavenger",
                    "Zooplanktivore",
                    "ML",

                ]
            ],
            [
                'name' => 'trophic guild 1',
                'survey_program_id' => $surveyProgram->id,
                'type' => 'select',
                'values' => [
                    "inv",
                    "grazer",
                    "omnivorous detritivores",
                    "he",
                    "cnidaris or sponges",
                    "opportunistic feeder, exhibiting both carnivory and scavenging",
                    "omnivorous scavenger",
                    "detritivorous",
                    "Predator/Scavenger",
                    "filter feeders",
                    "Mobile carnivorous species "
                ]
            ],
            [
                'name' => 'trophic guild 2',
                'survey_program_id' => $surveyProgram->id,
                'type' => 'select',
                'values' => [
                    "Predator-omnivorous, polytrophic",
                    "Grazer-mostly herviborous",
                    "Predator-carnivorous",
                    "Detritivore",
                    "Grazer - mostly herviborous",
                    "Grazer-carnivorous",
                    "Polytrophic-planktivore and symbiosis with zooxanthellae",
                    "Grazer-herviborous",
                    "Filter feeder-planktivore ",
                    "Detritivore-herbivorous",

                ]
            ],
            [
                'name' => 'trophic guild 3',
                'survey_program_id' => $surveyProgram->id,
                'type' => 'select',
                'values' => [
                    "Invertivore",
                    "Grazer",
                    "Detritivore",
                    "Herbivore",
                    "Scavenger",
                    "Zooplanktivore",
                    "Planktivore",
                    "Macro-carnivore",
                    "Omnivore",
                ]
            ],
            ['name' => 'group', 'survey_program_id' => $surveyProgram->id, 'type' => 'select', 'values' => [
                'fish',
                'cnidaria',
                'echinodermata',
                'gastropoda',
                'Crustacea',
                'polychaeta',
                'Mollusca',
                'bivalvia',
                'ML',
            ]],
            [
                'name' => 'a',
                'survey_program_id' => $surveyProgram->id,
                'type' => 'number'
            ],
            [
                'name' => 'b',
                'survey_program_id' => $surveyProgram->id,
                'type' => 'number'
            ],
            [
                'name' => 'a-b source',
                'survey_program_id' => $surveyProgram->id,
                'type' => 'text'
            ],
        ];

        foreach ($indicators as $indicator) {
            $indicatorEl = Indicator::create([
                'name' => $indicator["name"],
                'survey_program_id' => $indicator["survey_program_id"],
                'type' => $indicator["type"],
            ]);

            if (array_key_exists("values", $indicator)) {
                foreach ($indicator["values"] as $value) {
                    IndicatorHasValue::create([
                        "name" => $value,
                        "indicator_id" => $indicatorEl->id
                    ]);
                }
            }
        }

        // $trophicGuilds = [
        //     ['name' => 'Omnivore'],
        //     ['name' => 'Invertivore'],
        //     ['name' => 'Macro-carnivore'],
        //     ['name' => 'Grazer'],
        //     ['name' => 'Piscivore'],
        //     ['name' => 'Scavenger'],
        //     ['name' => 'Herbivore'],
        //     ['name' => 'Planktivore'],
        //     ['name' => 'Detritivore'],
        //     ['name' => 'Zooplanktivore'],
        // ];

        // foreach ($trophicGuilds as $trophicGuild) {
        //     TrophicGuild::create($trophicGuild);
        // }

        ## macroinv, fish, litter ....
        $categories = [
            [
                'macroinv',
                [
                    [
                        'name' => 'Pachymatisma johnstonia',
                        'genus' => 'Pachymatisma',
                        'species' => 'johnstonia'
                    ],
                    [
                        'name' => 'Ventroma halecioides',
                        'genus' => 'Ventroma',
                        'species' => 'halecioides'
                    ],
                    [
                        'name' => 'Spirobranchius triqueter',
                        'genus' => 'Spirobranchius',
                        'species' => 'triqueter'
                    ],
                    [
                        'name' => 'Salmacina dysteri',
                        'genus' => 'Salmacina',
                        'species' => 'dysteri'
                    ],
                    [
                        'name' => "Centrostephanus longispinus",
                        'genus' => 'Centrostephanus',
                        'species' => 'longispinus',
                        'phylum' => 'Echinodermata',
                    ],
                    [
                        'name' => 'Anemonia sulcata',
                        'genus' => 'Anemonia',
                        'species' => 'sulcata',
                        'phylum' => 'Cnidaria'
                    ],
                    [
                        'name' => 'Arbacia lixula',
                        'genus' => 'Arbacia',
                        'species' => 'lixula',
                        'phylum' => 'Echinodermata'
                    ],
                    [
                        'name' => 'Charonia tritonis',
                        'genus' => 'Charonia',
                        'species' => 'tritonis',
                        'phylum' => 'Mollusca'
                    ],
                    [
                        'name' => 'Coscinasterias tenuispina',
                        'genus' => 'Coscinasterias',
                        'species' => 'tenuispina',
                        'phylum' => 'Echinodermata'
                    ],
                    [
                        'name' => 'Dardanus calidus',
                        'genus' => 'Dardanus',
                        'species' => 'calidus',
                        'phylum' => 'Crustacea'
                    ],
                    [
                        'name' => 'Diadema africanum',
                        'genus' => 'Diadema',
                        'species' => 'africanum',
                        'phylum' => 'Echinodermata'
                    ],
                    [
                        'name' => 'Felimare picta',
                        'genus' => 'Felimare',
                        'species' => 'picta',
                        'phylum' => 'Mollusca'
                    ],
                    [
                        'name' => 'Hermodice  carunculata',
                        'genus' => 'Hermodice ',
                        'species' => 'carunculata',
                        'phylum' => 'Polychaeta'
                    ],
                    [
                        'name' => 'Hexaplex trunculus',
                        'genus' => 'Hexaplex',
                        'species' => 'trunculus',
                        'phylum' => 'Mollusca'
                    ],
                    [
                        'name' => 'Holothuria sp.',
                        'genus' => 'Holothuria',
                        'species' => 'sp.',
                        'phylum' => 'Echinodermata'
                    ],
                    [
                        'name' => 'Luidia ciliaris',
                        'genus' => 'Luidia',
                        'species' => 'ciliaris',
                        'phylum' => 'Echinodermata'
                    ],
                    [
                        'name' => 'Madracis asperula',
                        'genus' => 'Madracis',
                        'species' => 'asperula',
                        'phylum' => 'Cnidaria'
                    ],
                    [
                        'name' => 'Marthasterias  glacialis',
                        'genus' => 'Marthasterias ',
                        'species' => 'glacialis',
                        'phylum' => 'Echinodermata'
                    ],
                    [
                        'name' => 'Octopus vulgaris',
                        'genus' => 'Octopus',
                        'species' => 'vulgaris',
                        'phylum' => 'Mollusca'
                    ],
                    [
                        'name' => 'Ophidiaster ophidianus',
                        'genus' => 'Ophidiaster',
                        'species' => 'ophidianus',
                        'phylum' => 'Echinodermata'
                    ],
                    [
                        'name' => 'Ophioderma longicauda',
                        'genus' => 'Ophioderma',
                        'species' => 'longicauda',
                        'phylum' => 'Echinodermata'
                    ],
                    [
                        'name' => 'Pagurus sp.',
                        'genus' => 'Pagurus',
                        'species' => 'sp.',
                        'phylum' => 'Crustacea'
                    ],
                    [
                        'name' => 'Paracentrotus lividus',
                        'genus' => 'Paracentrotus',
                        'species' => 'lividus',
                        'phylum' => 'Echinodermata'
                    ],
                    [
                        'name' => 'Percnon gibbesi',
                        'genus' => 'Percnon',
                        'species' => 'gibbesi',
                        'phylum' => 'Crustacea'
                    ],
                    [
                        'name' => 'Pinna rudis',
                        'genus' => 'Pinna',
                        'species' => 'rudis',
                        'phylum' => 'Mollusca'
                    ],
                    [
                        'name' => 'Semicassis granulata',
                        'genus' => 'Phalium',
                        'species' => 'granulatum',
                        'phylum' => 'Mollusca'
                    ],
                    [
                        'name' => 'Sepia officinalis',
                        'genus' => 'Sepia',
                        'species' => 'officinalis',
                        'phylum' => 'Mollusca'
                    ],
                    [
                        'name' => 'Sphaerechinus granularis',
                        'genus' => 'Sphaerechinus',
                        'species' => 'granularis',
                        'phylum' => 'Echinodermata'
                    ],
                    [
                        'name' => 'Stenorhyncus lanceolatus',
                        'genus' => 'Stenorhyncus',
                        'species' => 'lanceolatus',
                        'phylum' => 'Crustacea'
                    ],
                    [
                        'name' => 'Telmatactis cricoides',
                        'genus' => 'Telmatactis',
                        'species' => 'cricoides',
                        'phylum' => 'Cnidaria'
                    ],
                    [
                        'name' => 'Aglaophenia pluma',
                        'genus' => 'Aglaophenia',
                        'species' => 'Aglaophenia pluma'
                    ],
                    [
                        'name' => 'Aplysina aerophoba',
                        'genus' => 'Aplysina',
                        'species' => 'Aplysina aerophoba'
                    ],
                    [
                        'name' => 'Balanus sp.',
                        'genus' => 'Balanus',
                        'species' => 'Balanus sp.'
                    ],
                    [
                        'name' => 'Balanus trigonous',
                        'genus' => 'Balanus',
                        'species' => 'trigonous'
                    ],
                    [
                        'name' => 'Barnacle',
                        'genus' => 'Barnacle',
                        'species' => ''
                    ],
                    [
                        'name' => 'Batzella inops',
                        'genus' => 'Batzella',
                        'species' => 'Batzella inops'
                    ],
                    [
                        'name' => 'Calcinus tubularis',
                        'genus' => 'Calcinus',
                        'species' => 'Calcinus tubularis'
                    ],
                    [
                        'name' => 'Calcinus tubularis',
                        'genus' => 'Calcinus',
                        'species' => 'Calcinus tubularis'
                    ],
                    [
                        'name' => 'Cliona cf. Celata',
                        'genus' => 'Cliona',
                        'species' => 'Cliona cf. Celata'
                    ],
                    [
                        'name' => 'Cliona viridis',
                        'genus' => 'Cliona',
                        'species' => 'Cliona viridis'
                    ],
                    [
                        'name' => 'Condrosia reniformis',
                        'genus' => 'Condrosia',
                        'species' => 'Condrosia reniformis'
                    ],
                    [
                        'name' => 'Crambe crambe',
                        'genus' => 'Crambe',
                        'species' => ''
                    ],
                    [
                        'name' => 'Diadema vermetidae',
                        'genus' => 'Diadema',
                        'species' => 'Diadema vermetidae'
                    ],
                    [
                        'name' => 'Diadema africanum',
                        'genus' => 'Diadema',
                        'species' => 'Diadema africanum'
                    ],
                    [
                        'name' => 'Distaplia corolla',
                        'genus' => 'Distaplia',
                        'species' => ''
                    ],
                    [
                        'name' => 'Gastropod',
                        'genus' => 'Gastropod',
                        'species' => ''
                    ],
                    [
                        'name' => 'Hermodice carunculata',
                        'genus' => 'Hermodice',
                        'species' => ''
                    ],
                    [
                        'name' => 'Kirchenpaueria halecioides',
                        'genus' => 'Kirchenpaueria',
                        'species' => 'Kirchenpaueria halecioides'
                    ],
                    [
                        'name' => 'Macrorhynchia philippina',
                        'genus' => 'Macrorhynchia',
                        'species' => ''
                    ],
                    [
                        'name' => 'Marthasterias glacialis',
                        'genus' => 'Marthasterias',
                        'species' => ''
                    ],
                    [
                        'name' => 'Paracentrotus lividus',
                        'genus' => 'Paracentrotus',
                        'species' => ''
                    ],
                    [
                        'name' => 'Pennaria disticha',
                        'genus' => 'Pennaria',
                        'species' => ''
                    ],
                    [
                        'name' => 'Percnon gibbesi',
                        'genus' => 'Percnon',
                        'species' => ''
                    ],
                    [
                        'name' => 'Phorbas fictitius',
                        'genus' => 'Phorbas',
                        'species' => ''
                    ],
                    [
                        'name' => 'Pina rudis',
                        'genus' => 'Pina',
                        'species' => ''
                    ],
                    [
                        'name' => 'Reptadeonella violacea',
                        'genus' => 'Reptadeonella',
                        'species' => ''
                    ],
                    [
                        'name' => 'Reptadeonella violacea w/ TS/film',
                        'genus' => 'Reptadeonella',
                        'species' => ''
                    ],
                    [
                        'name' => 'Sarcotragus spinolosus',
                        'genus' => 'Sarcotragus',
                        'species' => ''
                    ],
                    [
                        'name' => 'Schizoporella (orange) cf. longirostris',
                        'genus' => 'Schizoporella',
                        'species' => ''
                    ],
                    [
                        'name' => 'Schizoporella sp.',
                        'genus' => 'Schizoporella',
                        'species' => ''
                    ],
                    [
                        'name' => 'Schizoporella sp. (malhada)',
                        'genus' => 'Schizoporella',
                        'species' => ''
                    ],
                    [
                        'name' => 'Schizoporella sp. (orange)',
                        'genus' => 'Schizoporella',
                        'species' => ''
                    ],
                    [
                        'name' => 'Serpulidae',
                        'genus' => 'Serpulidae',
                        'species' => ''
                    ],
                    [
                        'name' => 'Sphaerachinus',
                        'genus' => 'Sphaerachinus',
                        'species' => ''
                    ],
                    [
                        'name' => 'Spirorbidae',
                        'genus' => 'Spirorbidae',
                        'species' => ''
                    ],
                    [
                        'name' => 'Turfed Silt w/ A. Pluma',
                        'genus' => 'Turfed Silt w/ A. Pluma',
                        'species' => ''
                    ],
                    [
                        'name' => 'Unk. Black hydrozoan',
                        'genus' => 'Unk. Black hydrozoan',
                        'species' => ''
                    ],
                    [
                        'name' => 'Unknown anemone',
                        'genus' => 'anemone',
                        'species' => ''
                    ],
                    [
                        'name' => 'Unknown barnacle',
                        'genus' => 'barnacle',
                        'species' => ''
                    ],
                    [
                        'name' => 'Unknown bryozoan',
                        'genus' => 'bryozoan',
                        'species' => ''
                    ],
                    [
                        'name' => 'Unknown Hydrozoa',
                        'genus' => 'Hydrozoan',
                        'species' => ''
                    ],
                    [
                        'name' => 'Unknown sponge',
                        'genus' => 'Sponge',
                        'species' => ''
                    ],
                    [
                        'name' => 'Unknown macroinvertebrate',
                        'genus' => 'macroinvertebrate',
                        'species' => ''
                    ],
                    [
                        'name' => 'Vermetidae',
                        'genus' => 'Vermetidae',
                        'species' => 'Vermetidae'
                    ],
                    [
                        'name' => 'Patella sp.',
                        'genus' => 'Patella',
                        'species' => 'Patella sp.'
                    ],
                    [
                        'name' => 'Berthellina edwardsii',
                        'genus' => 'Berthellina',
                        'species' => 'edwardsii'
                    ],
                    [
                        'name' => 'Brachycarpus biunguiculatus',
                        'genus' => 'Brachycarpus',
                        'species' => 'biunguiculatus'
                    ],
                    [
                        'name' => 'Brachycarpus sp.',
                        'genus' => 'Brachycarpus',
                        'species' => 'sp.'
                    ],
                    [
                        'name' => 'Charonia tritonis',
                        'genus' => 'Charonia',
                        'species' => 'tritonis'
                    ],
                    [
                        'name' => 'Charonia sp.',
                        'genus' => 'Charonia',
                        'species' => 'sp.'
                    ],
                    [
                        'name' => 'Coscinasterias tenuispina',
                        'genus' => 'Coscinasterias',
                        'species' => 'tenuispina'
                    ],

                    [
                        'name' => 'Cronius ruber',
                        'genus' => 'Cronius',
                        'species' => 'ruber'
                    ],
                    [
                        'name' => 'Dardanus calidus',
                        'genus' => 'Dardanus',
                        'species' => 'calidus'
                    ],
                    [
                        'name' => 'Diadema africanum',
                        'genus' => 'Diadema',
                        'species' => 'africanum'
                    ],
                    [
                        'name' => 'Hermodice carunculata',
                        'genus' => 'Hermodice ',
                        'species' => 'carunculata'
                    ],
                    [
                        'name' => 'Felimare picta',
                        'genus' => 'Felimare',
                        'species' => 'picta'
                    ],
                    [
                        'name' => 'Hexaplex trunculus',
                        'genus' => 'Hexaplex',
                        'species' => 'trunculus'
                    ],
                    [
                        'name' => 'Holothuria sp.',
                        'genus' => 'Holothuria',
                        'species' => 'sp.'
                    ],
                    [
                        'name' => 'Octopus vulgaris',
                        'genus' => 'Octopus',
                        'species' => 'vulgaris'
                    ],
                    [
                        'name' => 'Ophidiaster ophidianus',
                        'genus' => 'Ophidiaster',
                        'species' => 'ophidianus'
                    ],
                    [
                        'name' => 'Ophioderma longicauda',
                        'genus' => 'Ophioderma',
                        'species' => 'longicauda'
                    ],
                    [
                        'name' => 'Pagurus sp.',
                        'genus' => 'Pagurus',
                        'species' => 'sp.'
                    ],
                    [
                        'name' => 'Paracentrotus lividus',
                        'genus' => 'Paracentrotus',
                        'species' => 'lividus'
                    ],
                    [
                        'name' => 'Percnon gibbesi',
                        'genus' => 'Percnon',
                        'species' => 'gibbesi'
                    ],
                    [
                        'name' => 'Pinna rudis',
                        'genus' => 'Pinna',
                        'species' => 'rudis'
                    ],
                    [
                        'name' => 'Sepia officinalis',
                        'genus' => 'Sepia',
                        'species' => 'officinalis'
                    ],
                ]
            ],
            [
                'substrate',
                [
                    [
                        'name' => 'Rubble',
                        'genus' => 'Rubble',
                        'species' => 'Rubble'
                    ],
                    [
                        'name' => 'Sand',
                        'genus' => 'Sand',
                        'species' => 'Sand'
                    ],
                    [
                        'name' => 'Silt',
                        'genus' => 'Silt',
                        'species' => ''
                    ],
                    [
                        'name' => 'Spongionella cf. pulchella',
                        'genus' => 'Spongionella',
                        'species' => ''
                    ],
                ],
            ],
            [
                'algae',
                [
                    [
                        'name' => 'Lithophyllum sp.',
                        'genus' => 'Lithophyllum',
                        'species' => 'sp.'
                    ],
                    [
                        'name' => 'Acetabularia sp.',
                        'genus' => 'Acetabularia',
                        'species' => 'Acetabularia sp.'
                    ],
                    [
                        'name' => 'Asparagopsis sp.',
                        'genus' => 'Asparagopsis',
                        'species' => ''
                    ],
                    [
                        'name' => 'Asparagopsis taxiformis',
                        'genus' => 'Asparagopsis',
                        'species' => ''
                    ],
                    [
                        'name' => 'Blade like CCA',
                        'genus' => 'Blade like CCA',
                        'species' => ''
                    ],

                    [
                        'name' => 'Caulerpa webbiana',
                        'genus' => 'Caulerpa',
                        'species' => ''
                    ],
                    [
                        'name' => 'CCA',
                        'genus' => 'CCA',
                        'species' => 'CCA'
                    ],
                    [
                        'name' => 'Ceramium sp.',
                        'genus' => 'Ceramium',
                        'species' => 'Ceramium sp.'
                    ],

                    [
                        'name' => 'Codium adherens',
                        'genus' => 'Codium',
                        'species' => 'Codium adherens'
                    ],
                    [
                        'name' => 'Colpomenia sinuosa',
                        'genus' => 'Colpomenia',
                        'species' => 'Colpomenia sinuosa'
                    ],

                    [
                        'name' => 'Corallina sp.',
                        'genus' => 'Corallina',
                        'species' => ''
                    ],
                    [
                        'name' => 'Coralline turf',
                        'genus' => 'Coralline',
                        'species' => ''
                    ],
                    [
                        'name' => 'Cotoniella filamentosa',
                        'genus' => 'Cotoniella',
                        'species' => ''
                    ],

                    [
                        'name' => 'Cyanobacteria / diatoms',
                        'genus' => 'Cyanobacteria',
                        'species' => ''
                    ],
                    [
                        'name' => 'Cystoseira abies-marina',
                        'genus' => 'Cystoseira',
                        'species' => ''
                    ],
                    [
                        'name' => 'Dasycladus vermicularis',
                        'genus' => 'Dasycladus',
                        'species' => ''
                    ],
                    [
                        'name' => 'Dead CCA',
                        'genus' => 'Dead CCA',
                        'species' => ''
                    ],

                    [
                        'name' => 'Dictyopteris sp.',
                        'genus' => 'Dictyopteris',
                        'species' => ''
                    ],
                    [
                        'name' => 'Dictyota bartayresiana',
                        'genus' => 'Dictyota',
                        'species' => ''
                    ],
                    [
                        'name' => 'Dictyota dichotoma',
                        'genus' => 'Dictyota',
                        'species' => ''
                    ],
                    [
                        'name' => 'Dictyota sp.',
                        'genus' => 'Dictyota',
                        'species' => ''
                    ],

                    [
                        'name' => 'Halopteris filicina',
                        'genus' => 'Halopteris',
                        'species' => ''
                    ],
                    [
                        'name' => 'Halopteris sp.',
                        'genus' => 'Halopteris',
                        'species' => ''
                    ],
                    [
                        'name' => 'Halopteris Turf',
                        'genus' => 'Halopteris',
                        'species' => ''
                    ],

                    [
                        'name' => 'Hydroclathrus clathratus',
                        'genus' => 'Hydroclathrus',
                        'species' => ''
                    ],
                    [
                        'name' => 'Jania sp.',
                        'genus' => 'Jania',
                        'species' => ''
                    ],
                    [
                        'name' => 'Liagora sp.',
                        'genus' => 'Liagora',
                        'species' => ''
                    ],
                    [
                        'name' => 'Lobophora Turf',
                        'genus' => 'Lobophora',
                        'species' => ''
                    ],
                    [
                        'name' => 'Lobophora variegata',
                        'genus' => 'Lobophora',
                        'species' => ''
                    ],

                    [
                        'name' => 'Nemoderma sp.',
                        'genus' => 'Nemoderma',
                        'species' => ''
                    ],
                    [
                        'name' => 'Padina pavonica',
                        'genus' => 'Padina',
                        'species' => ''
                    ],

                    [
                        'name' => 'Pterocladdiella sp.',
                        'genus' => 'Pterocladdiella',
                        'species' => ''
                    ],
                    [
                        'name' => 'Ralfsia cf. Verrucosa',
                        'genus' => 'Ralfsia',
                        'species' => ''
                    ],
                    [
                        'name' => 'Ralfsia sp.',
                        'genus' => 'Ralfsia',
                        'species' => ''
                    ],

                    [
                        'name' => 'Rodolith',
                        'genus' => 'Rodolith',
                        'species' => ''
                    ],

                    [
                        'name' => 'Sargassum sp.',
                        'genus' => 'Sargassum',
                        'species' => ''
                    ],


                    [
                        'name' => 'Stypopodium zonale',
                        'genus' => 'Stypopodium',
                        'species' => ''
                    ],
                    [
                        'name' => 'Turf',
                        'genus' => 'Turf',
                        'species' => ''
                    ],
                    [
                        'name' => 'Turf w/ L.variegata',
                        'genus' => 'Turf',
                        'species' => ''
                    ],
                    [
                        'name' => 'Turfed silt',
                        'genus' => 'Turfed silt',
                        'species' => ''
                    ],
                    [
                        'name' => 'Unknown algae',
                        'genus' => 'algae',
                        'species' => ''
                    ],

                    [
                        'name' => 'Unknown Coralline Algae',
                        'genus' => 'coralline algae',
                        'species' => ''
                    ],
                    [
                        'name' => 'Unknown cyanobacteria',
                        'genus' => 'cyanobacteria',
                        'species' => ''
                    ],

                    [
                        'name' => 'Zonaria tournefortii',
                        'genus' => 'Zonaria',
                        'species' => ''
                    ],
                    [
                        'name' => 'Lobophora delicata',
                        'genus' => 'Lobophora',
                        'species' => 'Lobophora delicata'
                    ],
                    [
                        'name' => 'Halopteris scoparia',
                        'genus' => 'Halopteris',
                        'species' => 'Halopteris scoparia'
                    ],
                ]
            ],
            [
                'fish',
                [
                    [
                        'name' => 'Abudefduf luridus',
                        'genus' => 'Abudefduf',
                        'species' => 'luridus'
                    ],
                    [
                        'name' => 'Aluterus scriptus',
                        'genus' => 'Aluterus',
                        'species' => 'scriptus'
                    ],
                    [
                        'name' => 'Antennarius nummifer',
                        'genus' => 'Antennarius ',
                        'species' => 'nummifer'
                    ],
                    [
                        'name' => 'Apogon imberbis',
                        'genus' => 'Apogon',
                        'species' => 'imberbis'
                    ],

                    [
                        'name' => 'Atherina presbyther',
                        'genus' => 'Atherina',
                        'species' => 'presbyther'
                    ],
                    [
                        'name' => 'Atherina sp.',
                        'genus' => 'Atherina',
                        'species' => 'sp.'
                    ],
                    [
                        'name' => 'Aulostomus strigosus',
                        'genus' => 'Aulostomus',
                        'species' => 'strigosus'
                    ],
                    [
                        'name' => 'Balistes capriscus',
                        'genus' => 'Balistes',
                        'species' => 'capriscus'
                    ],
                    [
                        'name' => 'Boops boops',
                        'genus' => 'Boops',
                        'species' => 'boops'
                    ],
                    [
                        'name' => 'Bothus podas',
                        'genus' => 'Bothus',
                        'species' => 'podas'
                    ],
                    [
                        'name' => 'Canthigaster capistrata',
                        'genus' => 'Canthigaster',
                        'species' => 'capistrata'
                    ],
                    [
                        'name' => 'Centrolabrus caeruleus',
                        'genus' => 'Centrolabrus',
                        'species' => 'caeruleus'
                    ],
                    [
                        'name' => 'Centrolabrus trutta',
                        'genus' => 'Centrolabrus',
                        'species' => 'trutta'
                    ],

                    [
                        'name' => 'Chelidonichthys obscurus',
                        'genus' => 'Chelidonichthys',
                        'species' => 'obscurus'
                    ],
                    [
                        'name' => 'Chelon labrosus',
                        'genus' => 'Chelon',
                        'species' => 'labrosus'
                    ],

                    [
                        'name' => 'Chromis limbata',
                        'genus' => 'Chromis',
                        'species' => 'limbata'
                    ],
                    [
                        'name' => 'Coris julis',
                        'genus' => 'Coris',
                        'species' => 'julis'
                    ],
                    [
                        'name' => 'Dasyatis pastinaca',
                        'genus' => 'Dasyatis',
                        'species' => 'pastinaca'
                    ],
                    [
                        'name' => 'Dentex dentex',
                        'genus' => 'Dentex',
                        'species' => 'dentex'
                    ],

                    [
                        'name' => 'Diplodus  sp.',
                        'genus' => 'Diplodus ',
                        'species' => 'sp.'
                    ],
                    [
                        'name' => 'Diplodus cervinus',
                        'genus' => 'Diplodus',
                        'species' => 'cervinus'
                    ],
                    [
                        'name' => 'Diplodus sargus',
                        'genus' => 'Diplodus',
                        'species' => 'sargus'
                    ],
                    [
                        'name' => 'Diplodus vulgaris',
                        'genus' => 'Diplodus',
                        'species' => 'vulgaris'
                    ],

                    [
                        'name' => 'Enchelycore anatina',
                        'genus' => 'Enchelycore',
                        'species' => 'anatina'
                    ],
                    [
                        'name' => 'Epinephelus marginatus',
                        'genus' => 'Epinephelus',
                        'species' => 'marginatus'
                    ],


                    [
                        'name' => 'Heteropriacanthus cruentatus',
                        'genus' => 'Heteropriacanthus',
                        'species' => 'cruentatus'
                    ],

                    [
                        'name' => 'Labridae sp.',
                        'genus' => 'Labridae',
                        'species' => 'sp.'
                    ],
                    [
                        'name' => 'Lithognathus mormyrus',
                        'genus' => 'Lithognathus',
                        'species' => 'mormyrus'
                    ],

                    [
                        'name' => 'Mullus surmuletus',
                        'genus' => 'Mullus',
                        'species' => 'surmuletus'
                    ],
                    [
                        'name' => 'Muraena augusti',
                        'genus' => 'Muraena',
                        'species' => 'augusti'
                    ],
                    [
                        'name' => 'Mycteroperca fusca',
                        'genus' => 'Mycteroperca',
                        'species' => 'fusca'
                    ],
                    [
                        'name' => 'Oblada melanura',
                        'genus' => 'Oblada',
                        'species' => 'melanura'
                    ],

                    [
                        'name' => 'Ophioblennius  atlanticus',
                        'genus' => 'Ophioblennius ',
                        'species' => 'atlanticus'
                    ],

                    [
                        'name' => 'Pagellus acarne',
                        'genus' => 'Pagellus',
                        'species' => 'acarne'
                    ],
                    [
                        'name' => 'Pagellus erythrinus',
                        'genus' => 'Pagellus',
                        'species' => 'erythrinus'
                    ],
                    [
                        'name' => 'Pagrus pagrus',
                        'genus' => 'Pagrus',
                        'species' => 'pagrus'
                    ],


                    [
                        'name' => 'Parapristipoma octolineatum',
                        'genus' => 'Parapristipoma ',
                        'species' => 'octolineatum'
                    ],
                    [
                        'name' => 'Pargus auriga',
                        'genus' => 'Pargus',
                        'species' => 'auriga'
                    ],

                    [
                        'name' => 'Pomadasys incisus',
                        'genus' => 'Pomadasys',
                        'species' => 'incisus'
                    ],
                    [
                        'name' => 'Pseudocaranx dentex',
                        'genus' => 'Pseudocaranx',
                        'species' => 'dentex'
                    ],
                    [
                        'name' => 'Sarpa salpa',
                        'genus' => 'Sarpa',
                        'species' => 'salpa'
                    ],
                    [
                        'name' => 'Scorpaena maderensis',
                        'genus' => 'Scorpaena',
                        'species' => 'maderensis'
                    ],
                    [
                        'name' => 'Scorpaena sp.',
                        'genus' => 'Scorpaena',
                        'species' => 'sp.'
                    ],
                    [
                        'name' => 'Semicassis granulata',
                        'genus' => 'Semicassis',
                        'species' => 'granulatum'
                    ],

                    [
                        'name' => 'Seriola rivoliana',
                        'genus' => 'Seriola',
                        'species' => 'rivoliana'
                    ],
                    [
                        'name' => 'Serranus atricauda',
                        'genus' => 'Serranus',
                        'species' => 'atricauda'
                    ],
                    [
                        'name' => 'Sparisoma cretense',
                        'genus' => 'Sparisoma',
                        'species' => 'cretense'
                    ],
                    [
                        'name' => 'Sphoeroides marmoratus',
                        'genus' => 'Sphoeroides',
                        'species' => 'marmoratus'
                    ],

                    [
                        'name' => 'Symphodus cinereus',
                        'genus' => 'Symphodus',
                        'species' => 'cinereus'
                    ],
                    [
                        'name' => 'Synodus saurus',
                        'genus' => 'Synodus',
                        'species' => 'saurus'
                    ],
                    [
                        'name' => 'Synodus sp.',
                        'genus' => 'Synodus',
                        'species' => 'sp.'
                    ],
                    [
                        'name' => 'Synodus synodus',
                        'genus' => 'Synodus',
                        'species' => 'synodus'
                    ],
                    [
                        'name' => 'Thalassoma pavo',
                        'genus' => 'Thalassoma',
                        'species' => 'pavo'
                    ],
                    [
                        'name' => 'Tripterygion delaisi',
                        'genus' => 'Tripterygion',
                        'species' => 'delaisi'
                    ],
                    [
                        'name' => 'Unknown fish',
                        'genus' => 'fish',
                        'species' => ''
                    ],
                ]
            ],
            [
                'litter',
                [
                    ['name' => 'Plastic'],
                    ['name' => 'Rubber'],
                    ['name' => 'Cloth/Textile'],
                    ['name' => 'Paper/Cardboard'],
                    ['name' => 'Processed/Worked Wood'],
                    ['name' => 'Metal'],
                    ['name' => 'Glass/Ceramics'],
                ]
            ],
            [
                'other',
                [
                    [
                        'name' => 'Bare',
                        'genus' => 'Bare',
                        'species' => ''
                    ],
                    [
                        'name' => 'Unknown',
                        'genus' => 'Unknown',
                        'species' => ''
                    ],
                ]
            ]
        ];

        foreach ($categories as $category) {
            $nCategory = TaxaCategory::create([
                'name' => $category[0],
                'survey_program_id' => $surveyProgram->id,
            ]);

            foreach ($category[1] as $taxa) {
                $taxaAlreadyExists = Taxa::where('name', $taxa['name'])->first();

                if (!$taxaAlreadyExists)
                    Taxa::create([
                        'name' => $taxa["name"],
                        'genus' => $taxa["genus"] ?? null,
                        'species' => $taxa["species"] ?? null,
                        'phylum' => array_key_exists("phylum", $taxa) ? $taxa["phylum"] : null,
                        'survey_program_id' => $surveyProgram->id,
                        'category_id' => $nCategory->id,
                        'validated' => true,
                    ]);
            }
        }

        $sizeCategories = [
            ['name' => '<5'],
            ['name' => '6-10'],
            ['name' => '11-20'],
            ['name' => '21-30'],
            ['name' => '31-40'],
            ['name' => '41-50'],
            ['name' => '51-70'],
            ['name' => '71-100'],
            ['name' => '>100'],
        ];

        foreach ($sizeCategories as $sizeCategory) {
            SizeCategory::create($sizeCategory);
        }

        $densities = [
            ['name' => '0-25'],
            ['name' => '25-50'],
            ['name' => '0-50'],
        ];

        foreach ($densities as $density) {
            Density::create($density);
        }

        $teamFunctions = [
            ['name' => 'fish', 'survey_program_id' => $surveyProgram->id],
            ['name' => 'cryptic', 'survey_program_id' => $surveyProgram->id],
            ['name' => 'macroinv', 'survey_program_id' => $surveyProgram->id],
            ['name' => 'dom_urchin', 'survey_program_id' => $surveyProgram->id],
            ['name' => 'benthic_t', 'survey_program_id' => $surveyProgram->id],
            ['name' => 'photo_q', 'survey_program_id' => $surveyProgram->id],
        ];

        foreach ($teamFunctions as $teamFunction) {
            SurveyProgramFunction::create($teamFunction);
        }

        $depths = [
            ['name' => '4-6 m', 'survey_program_id' => $surveyProgram->id],
            ['name' => '9-11 m', 'survey_program_id' => $surveyProgram->id],
            ['name' => '19-21 m', 'survey_program_id' => $surveyProgram->id],
        ];

        foreach ($depths as $depth) {
            Depth::create($depth);
        }


        $localities = [
            [
                'name' => 'South East',
                'code' => 'SE',
                'survey_program_id' => $surveyProgram->id,
                'sites' => [
                    ['name' => 'Quinta do Lorde W', 'code' => 'QLW',],
                    ['name' => 'Quinta do Lorde E', 'code' => 'QLE',],
                ]
            ],
            [
                'name' => 'South West',
                'code' => 'SW',
                'survey_program_id' => $surveyProgram->id,
                'sites' => [
                    ['name' => 'Faja da Ovelha', 'code' => 'FO',],
                    ['name' => 'Faja da Ovelha E', 'code' => 'FO_E',],
                    ['name' => 'Faja da Ovelha C', 'code' => 'FO_C',],
                ]
            ],

            [
                'name' => 'Caniço',
                'code' => 'C',
                'survey_program_id' => $surveyProgram->id,
                'sites' => [
                    ['name' => 'Reis Magos', 'code' => 'RM',],
                    ['name' => 'Atalaia', 'code' => 'AT',],
                ]
            ],

            [
                'name' => 'Funchal',
                'code' => 'FX',
                'survey_program_id' => $surveyProgram->id,
                'sites' => [
                    ['name' => 'Carlton', 'code' => 'CA',],
                    ['name' => 'Carlton E', 'code' => 'CA_E',],
                    ['name' => 'Carlton M', 'code' => 'CA_M',],
                    ['name' => 'Carlton W', 'code' => 'CA_W',],
                    ['name' => 'Palms', 'code' => 'PA',],
                ]
            ],

            [
                'name' => 'Cabo Girão',
                'code' => 'CG',
                'survey_program_id' => $surveyProgram->id,
                'sites' => [
                    ['name' => 'Câmara de Lobos', 'code' => 'CL',],
                    ['name' => 'Faja dos Padres', 'code' => 'FP',],
                ]
            ],

            [
                'name' => 'Porto Santo A',
                'code' => 'PXO_A',
                'survey_program_id' => $surveyProgram->id,
                'sites' => [
                    ['name' => 'LA01', 'code' => 'LA_01',],
                    ['name' => 'LA02', 'code' => 'LA_02',],
                    ['name' => 'LA03', 'code' => 'LA_03',],
                    ['name' => 'LA04', 'code' => 'LA_04',],
                    ['name' => 'LA05', 'code' => 'LA_05',],
                ]
            ],

            [
                'name' => 'Porto Santo C',
                'code' => 'PXO_C',
                'survey_program_id' => $surveyProgram->id,
                'sites' => [
                    ['name' => 'LC01', 'code' => 'LC_01',],
                    ['name' => 'LC02', 'code' => 'LC_02',],
                    ['name' => 'LC03', 'code' => 'LC_03',],
                    ['name' => 'LC04', 'code' => 'LC_04',],
                    ['name' => 'LC05', 'code' => 'LC_05',],
                ]
            ],

        ];

        foreach ($localities as $locality) {
            $createdLocality = Locality::create([
                "name" => $locality["name"],
                "code" => $locality["code"],
                "survey_program_id" => $locality["survey_program_id"],
            ]);

            foreach ($locality["sites"] as $site) {
                Site::create([
                    "name" => $site["name"],
                    "code" => $site["code"],
                    'locality_id' => $createdLocality->id
                ]);
            }
        }



        $substrates = [
            ['name' => 'block'],
            ['name' => 'rubble'],
            ['name' => 'boulder'],
            ['name' => 'platform'],
            ['name' => 'pavement'],
            ['name' => 'sand'],
            ['name' => 'gravel'],
            ['name' => 'rumble'],
        ];

        foreach ($substrates as $substrate) {
            Substrate::create($substrate);
        }

        $report = Report::create([
            "code" => "SE_QLW_Time0_D2_R0",
            "date" => "2017-05-17",
            "transect" => 0,
            "surveyed_area" => 100,
            "daily_dive" => 1,
            "time" => 0,
            "replica" => 0,
            "latitude" => 32.741018,
            "longitude" => -16.709188,
            "heading" => 70,
            "heading_direction" => null,
            "site_area" => null,
            "distance" => null,
            "dom_substrate" => "Blocks",
            "depth_id" => Depth::where("name", '9-11 m')->first()->id,
            "site_id" => Site::where("name", "Quinta do Lorde W")->first()->id,
            "survey_program_id" => $surveyProgram->id,
        ]);

        $report->functions()->attach(SurveyProgramFunction::all()->pluck('id'));
    }
}
