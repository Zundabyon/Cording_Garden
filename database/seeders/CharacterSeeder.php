<?php

namespace Database\Seeders;

use App\Models\Character;
use Illuminate\Database\Seeder;

class CharacterSeeder extends Seeder
{
    public function run(): void
    {
        $characters = [
            [
                'slug' => 'php',
                'name' => 'PHP子（ひめこ）',
                'gender' => 'female',
                'personality' => '優しくて面倒見が良い先輩タイプ。でも厳しい一面も。基本を大切にする真摯な姿勢が魅力。',
                'subject' => 'php',
                'description' => 'プログラミング部の先輩。PHPの達人で、後輩の面倒をよく見てくれる。実は完璧主義な一面があり、コードの品質には厳しい。',
                'is_unlocked' => true,
                'sort_order' => 1,
            ],
            [
                'slug' => 'laravel',
                'name' => 'Laravel先輩',
                'gender' => 'female',
                'personality' => '洗練されたお姉さんタイプ。エレガントで効率的。「優雅に、そして素早く」が信条。',
                'subject' => 'laravel',
                'description' => '生徒会長を務める完璧なお姉さま。Laravelフレームワークを使いこなし、どんな課題も優雅にこなす。PHP子先輩の良き相棒。',
                'is_unlocked' => true,
                'sort_order' => 2,
            ],
            [
                'slug' => 'html',
                'name' => 'HTML太郎',
                'gender' => 'male',
                'personality' => '素直で誠実。基礎をしっかり固める真面目タイプ。地道な努力を惜しまない。',
                'subject' => 'html',
                'description' => 'クラスの真面目な委員長。HTMLの構造を大切にし、「土台があってこそ」が口癖。最初は近寄りがたく見えるが、実は親切。',
                'is_unlocked' => false,
                'sort_order' => 3,
            ],
            [
                'slug' => 'css',
                'name' => 'CSS彩（あや）',
                'gender' => 'male',
                'personality' => 'おしゃれでこだわりが強い。見た目に敏感。デザインセンス抜群だが、時々こだわりすぎて迷子になる。',
                'subject' => 'css',
                'description' => '学校一のおしゃれ番長。服装やインテリアにこだわり、CSSで世界を美しく彩ることに情熱を注ぐ。',
                'is_unlocked' => false,
                'sort_order' => 4,
            ],
            [
                'slug' => 'js',
                'name' => 'JS翔（しょう）',
                'gender' => 'male',
                'personality' => 'ツンデレ。最初は冷たいが実は熱い。動的で予測不能な行動が多いが、根は純粋。',
                'subject' => 'js',
                'description' => 'クールを装っているが、実は面倒見の良い男の子。JavaScriptのように動的で、時に予測不能な言動をするが、困ったときには必ず助けてくれる。',
                'is_unlocked' => false,
                'sort_order' => 5,
            ],
            [
                'slug' => 'typescript',
                'name' => 'TypeScript誠（まこと）',
                'gender' => 'male',
                'personality' => 'JSの兄。メガネクイッの完璧主義者。弟を溺愛。型安全を極めた知性派。',
                'subject' => 'typescript',
                'description' => 'JS翔の兄。常にメガネをかけ、論理的で完璧主義。弟のことを溺愛していて、弟の友達には目を光らせている。TypeScriptの型システムを愛している。',
                'is_unlocked' => false,
                'sort_order' => 6,
            ],
            [
                'slug' => 'vue',
                'name' => 'Vue来（らい）',
                'gender' => 'male',
                'personality' => '帰国子女の秀才。ナチュラルでスマート。コンポーネント志向で物事を整理するのが得意。',
                'subject' => 'vue',
                'description' => '海外育ちの帰国子女。自然体でスマートな態度が人気を集める。Vueのコンポーネント思考で何でも分けて考える癖がある。',
                'is_unlocked' => false,
                'sort_order' => 7,
            ],
            [
                'slug' => 'error',
                'name' => 'Error404',
                'gender' => 'mysterious',
                'personality' => '謎多き風紀委員。エラーと問題を出してくる。見つからないと思ったら突然現れる不思議な存在。',
                'subject' => 'error',
                'description' => '学校の風紀委員。本当の名前は誰も知らない。バグやエラーを体現したような謎の存在で、主人公の前に予期せず現れては難題を出してくる。',
                'is_unlocked' => true,
                'sort_order' => 8,
            ],
        ];

        foreach ($characters as $character) {
            Character::updateOrCreate(['slug' => $character['slug']], $character);
        }
    }
}
