<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $categories = array(
        //     array('name' => 'Actors','slug' => 'actors','is_parent' => 0,'parent_id' => NULL,'status' => 1,'created_at' => now(),'updated_at' => now()),
        //     array('name' => 'Creators','slug' => 'creators','is_parent' => 0,'parent_id' => NULL,'status' => 1,'created_at' => now(),'updated_at' => now()),
        //     array('name' => 'Models','slug' => 'models','is_parent' => 0,'parent_id' => NULL,'status' => 1,'created_at' => now(),'updated_at' => now()),
        //     array('name' => 'Music','slug' => 'music','is_parent' => 0,'parent_id' => NULL,'status' => 1,'created_at' => now(),'updated_at' => now()),
        //     array('name' => 'Sports','slug' => 'sports','is_parent' => 0,'parent_id' => NULL,'status' => 1,'created_at' => now(),'updated_at' => now()),
        //     array('name' => 'TV/Radio','slug' => 'tvradio','is_parent' => 0,'parent_id' => NULL,'status' => 1,'created_at' => now(),'updated_at' => now())
        // );
        // Category::insert($categories);
        
        $categories = [
            'ACTORS' => [
                'ACTOR', 'ACTRESS', 'COMEDIAN', 'OTHER'
            ],
            'CREATORS' => [
                'INFLUENCER', 'BLOGGER', 'CONTENT', 'CREATOR', 'OTHER'
            ],
            'MODELS' => [
                'FASHION MODEL', 'INTERNET MODEL', 'SWIMSUIT & LINGERIE MODEL', 'FITNESS MODEL', 'BEAUTY PAGEANT MODEL', 'OTHER'
            ],
            'MUSIC (REPLACES ARTISTS)' => [
                'AFROBEATS', 'APALA', 'AZONTO', 'BENGA', 'BIKUTSI', 'BONGO FLAVA', 'COUPÉ DECALÉ', 'HIP HOP', 'KIZOMBA',
                'KUDURO', 'KWAITO', 'MAPOUKA', 'NDOMBOLA', 'POP', 'RAP', 'RUMBA', 'ZOUGLOU', 'OTHER'
            ],
            'SPORT (REPLACES ATHLETES)' => [
                'SOCCER', 'BASKETBALL', 'RUGBY', 'HANDBALL', 'VOLLEYBALL', 'SWIMMING', 'KARATE', 'JUDO', 'TAEKWONDO',
                'BOXING', 'MMA', 'TENNIS', 'GOLF', 'CYCLING', 'ATHLETICS', 'OTHER'
            ],
            'TV/RADIO' => [
                'JOURNALIST', 'HOST', 'DIRECTOR', 'PRODUCER', 'AUDIO', 'TECHNICIAN', 'OTHER'
            ]
        ];
        foreach ($categories as $key => $value) {
            $parent = Category::create([
                'name' => $key,
                'slug' => str()->slug($key),
                'is_parent' => 0,
                'parent_id' => NULL,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            foreach ($value as $sub) {
                Category::create([
                    'name' => $sub,
                    'slug' => str()->slug($sub),
                    'is_parent' => 1,
                    'parent_id' => $parent->id,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
