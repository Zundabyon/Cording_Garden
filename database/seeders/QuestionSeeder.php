<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $phpChar = Character::where('slug', 'php')->first();
        $laravelChar = Character::where('slug', 'laravel')->first();

        if (!$phpChar || !$laravelChar) {
            $this->command->warn('Characters not found. Run CharacterSeeder first.');
            return;
        }

        $phpQuestions = [
            [
                'character_id' => $phpChar->id,
                'category' => 'multiple_choice',
                'difficulty' => 1,
                'question_text' => 'PHPで配列の要素数を取得する関数は？',
                'code_snippet' => null,
                'options' => ['count()', 'length()', 'size()', 'sizeof()'],
                'correct_answer' => 'count()',
                'explanation' => 'count()関数はPHPで配列の要素数を取得します。sizeof()はcount()の別名です。',
            ],
            [
                'character_id' => $phpChar->id,
                'category' => 'multiple_choice',
                'difficulty' => 1,
                'question_text' => 'PHPで文字列を連結する演算子は？',
                'code_snippet' => null,
                'options' => ['.', '+', '&', '||'],
                'correct_answer' => '.',
                'explanation' => 'PHPでは文字列の連結にドット（.）演算子を使用します。',
            ],
            [
                'character_id' => $phpChar->id,
                'category' => 'multiple_choice',
                'difficulty' => 1,
                'question_text' => 'PHPで変数を宣言する際に使う記号は？',
                'code_snippet' => null,
                'options' => ['$', '#', '@', '&'],
                'correct_answer' => '$',
                'explanation' => 'PHPでは変数名の前に$記号を付けて宣言します（例: $name）。',
            ],
            [
                'character_id' => $phpChar->id,
                'category' => 'multiple_choice',
                'difficulty' => 2,
                'question_text' => 'PHPで連想配列を作成するコードはどれ？',
                'code_snippet' => null,
                'options' => [
                    '$arr = ["key" => "value"]',
                    '$arr = {"key": "value"}',
                    '$arr = [key: value]',
                    '$arr = new Array("key", "value")',
                ],
                'correct_answer' => '$arr = ["key" => "value"]',
                'explanation' => 'PHPの連想配列は => 演算子でキーと値を対応させます。',
            ],
            [
                'character_id' => $phpChar->id,
                'category' => 'multiple_choice',
                'difficulty' => 2,
                'question_text' => 'PHPで文字列の長さを取得する関数は？',
                'code_snippet' => null,
                'options' => ['strlen()', 'length()', 'strcount()', 'count()'],
                'correct_answer' => 'strlen()',
                'explanation' => 'strlen()はPHPで文字列のバイト数（長さ）を取得する関数です。',
            ],
            [
                'character_id' => $phpChar->id,
                'category' => 'multiple_choice',
                'difficulty' => 2,
                'question_text' => 'PHPで配列をソートする関数は？',
                'code_snippet' => null,
                'options' => ['sort()', 'order()', 'arrange()', 'asort()'],
                'correct_answer' => 'sort()',
                'explanation' => 'sort()はPHPの組み込み関数で、配列を昇順にソートします。',
            ],
            [
                'character_id' => $phpChar->id,
                'category' => 'fill_blank',
                'difficulty' => 1,
                'question_text' => 'PHPでHTMLに変数を埋め込む際、次のコードの空白を埋めてください: echo "Hello, ___ name ___"',
                'code_snippet' => '<?php $name = "World"; echo "Hello, ___ name ___"; ?>',
                'options' => ['{ $', '}', '<?= $name ?>', '${name}'],
                'correct_answer' => '<?= $name ?>',
                'explanation' => 'PHPテンプレートでは<?= $変数名 ?>の短縮タグで変数を出力できます。',
            ],
            [
                'character_id' => $phpChar->id,
                'category' => 'multiple_choice',
                'difficulty' => 3,
                'question_text' => 'PHPのforeachループの正しい構文は？',
                'code_snippet' => '$fruits = ["apple", "banana", "cherry"];',
                'options' => [
                    'foreach ($fruits as $fruit)',
                    'for each ($fruits as $fruit)',
                    'foreach $fruits as $fruit',
                    'loop ($fruits as $fruit)',
                ],
                'correct_answer' => 'foreach ($fruits as $fruit)',
                'explanation' => 'PHPのforeachは foreach (配列 as 変数) の構文で各要素を順番に取り出します。',
            ],
            [
                'character_id' => $phpChar->id,
                'category' => 'multiple_choice',
                'difficulty' => 3,
                'question_text' => 'PHPで例外処理を行うキーワードはどれ？',
                'code_snippet' => null,
                'options' => ['try/catch', 'begin/rescue', 'try/except', 'attempt/catch'],
                'correct_answer' => 'try/catch',
                'explanation' => 'PHPではtry-catchブロックで例外処理を行います。必要に応じてfinallyも使えます。',
            ],
            [
                'character_id' => $phpChar->id,
                'category' => 'multiple_choice',
                'difficulty' => 2,
                'question_text' => 'PHPで現在の日付と時刻を取得する関数は？',
                'code_snippet' => null,
                'options' => ['date()', 'now()', 'time()', 'datetime()'],
                'correct_answer' => 'date()',
                'explanation' => 'date()関数はフォーマット文字列を引数に取り、現在の日付・時刻を返します（例: date("Y-m-d")）。',
            ],
        ];

        $laravelQuestions = [
            [
                'character_id' => $laravelChar->id,
                'category' => 'multiple_choice',
                'difficulty' => 1,
                'question_text' => 'Laravelのルート定義ファイルはどこにありますか？',
                'code_snippet' => null,
                'options' => ['routes/web.php', 'app/routes.php', 'config/routes.php', 'resources/routes.php'],
                'correct_answer' => 'routes/web.php',
                'explanation' => 'Laravelのwebルートはroutes/web.phpに定義します。APIルートはroutes/api.phpです。',
            ],
            [
                'character_id' => $laravelChar->id,
                'category' => 'multiple_choice',
                'difficulty' => 1,
                'question_text' => 'LaravelのEloquentでレコードを全件取得するメソッドは？',
                'code_snippet' => null,
                'options' => ['all()', 'get()', 'find()', 'fetch()'],
                'correct_answer' => 'all()',
                'explanation' => 'Model::all()でそのモデルの全レコードを取得できます。',
            ],
            [
                'character_id' => $laravelChar->id,
                'category' => 'multiple_choice',
                'difficulty' => 1,
                'question_text' => 'Laravelのマイグレーションを実行するコマンドは？',
                'code_snippet' => null,
                'options' => ['php artisan migrate', 'php artisan db:migrate', 'laravel migrate', 'php artisan make:migrate'],
                'correct_answer' => 'php artisan migrate',
                'explanation' => 'php artisan migrateコマンドでデータベースマイグレーションを実行します。',
            ],
            [
                'character_id' => $laravelChar->id,
                'category' => 'multiple_choice',
                'difficulty' => 2,
                'question_text' => 'LaravelのBladeテンプレートでループを書く記法は？',
                'code_snippet' => null,
                'options' => ['@foreach($items as $item)', '{% for item in items %}', '{{#each items}}', '<% for item of items %>'],
                'correct_answer' => '@foreach($items as $item)',
                'explanation' => 'Bladeテンプレートでは@foreachディレクティブを使ってループを記述します。',
            ],
            [
                'character_id' => $laravelChar->id,
                'category' => 'multiple_choice',
                'difficulty' => 2,
                'question_text' => 'LaravelでModelを作成するコマンドは？',
                'code_snippet' => null,
                'options' => ['php artisan make:model User', 'php artisan create:model User', 'laravel make model User', 'php artisan model:create User'],
                'correct_answer' => 'php artisan make:model User',
                'explanation' => 'php artisan make:modelでEloquentモデルクラスを生成します。-mオプションでマイグレーションも同時作成できます。',
            ],
            [
                'character_id' => $laravelChar->id,
                'category' => 'multiple_choice',
                'difficulty' => 2,
                'question_text' => 'LaravelのEloquentで条件付き検索をするメソッドは？',
                'code_snippet' => null,
                'options' => ['where()', 'filter()', 'find()', 'search()'],
                'correct_answer' => 'where()',
                'explanation' => 'where()メソッドでSQL WHERE句を構築します。例: User::where("email", $email)->first()',
            ],
            [
                'character_id' => $laravelChar->id,
                'category' => 'multiple_choice',
                'difficulty' => 3,
                'question_text' => 'Laravelのミドルウェアで認証チェックをするものは？',
                'code_snippet' => null,
                'options' => ['auth', 'verified', 'guest', 'throttle'],
                'correct_answer' => 'auth',
                'explanation' => 'authミドルウェアを適用すると、未ログインユーザーはログインページにリダイレクトされます。',
            ],
            [
                'character_id' => $laravelChar->id,
                'category' => 'multiple_choice',
                'difficulty' => 3,
                'question_text' => 'LaravelのEloquentでリレーション（一対多）を定義するメソッドは？',
                'code_snippet' => null,
                'options' => ['hasMany()', 'belongsTo()', 'hasOne()', 'manyToMany()'],
                'correct_answer' => 'hasMany()',
                'explanation' => '一対多の場合、親モデルでhasMany()を定義し、子モデルでbelongsTo()を定義します。',
            ],
            [
                'character_id' => $laravelChar->id,
                'category' => 'fill_blank',
                'difficulty' => 2,
                'question_text' => 'Bladeテンプレートで変数を表示するには {{ $variable }} を使いますが、HTMLエスケープせずに出力するには？',
                'code_snippet' => null,
                'options' => ['{!! $variable !!}', '{{ $variable | raw }}', '{= $variable =}', '<%- variable %>'],
                'correct_answer' => '{!! $variable !!}',
                'explanation' => '{!! !!}はHTMLエスケープなしで出力します。XSS対策のため、信頼できるデータにのみ使用してください。',
            ],
            [
                'character_id' => $laravelChar->id,
                'category' => 'multiple_choice',
                'difficulty' => 1,
                'question_text' => 'LaravelのBladeテンプレートで条件分岐を書くディレクティブは？',
                'code_snippet' => null,
                'options' => ['@if / @else / @endif', '{% if %}{% endif %}', '<% if %><% end %>', '{{#if}}{{/if}}'],
                'correct_answer' => '@if / @else / @endif',
                'explanation' => 'Bladeでは@if, @elseif, @else, @endifを使って条件分岐を記述します。',
            ],
        ];

        foreach (array_merge($phpQuestions, $laravelQuestions) as $question) {
            Question::create($question);
        }
    }
}
