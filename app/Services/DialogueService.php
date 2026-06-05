<?php

namespace App\Services;

class DialogueService
{
    // 好感度0-100 各ポイントのセリフ定義
    // キー: slug, タイプ: greeting/correct/wrong
    // affection=0〜100 の各値に対応するセリフ

    public static function get(string $slug, string $type, int $affection): array
    {
        $affection = max(0, min(100, $affection));
        $data = static::getData($slug, $type);

        $keys = array_keys($data);
        $floor = 0;
        foreach ($keys as $k) {
            if ($k <= $affection) $floor = $k;
        }
        $result = $data[$floor] ?? [['text' => 'よろしく。', 'expression' => 'normal']];
        return static::pickVariant($result);
    }

    private static function pickVariant(mixed $entry): array
    {
        // 単一形式 ['text'=>..., 'expression'=>...] はそのまま返す
        if (isset($entry['text'])) {
            return $entry;
        }
        // 複数バリアント配列からランダム選択
        return $entry[array_rand($entry)];
    }

    private static function getData(string $slug, string $type): array
    {
        return match($slug) {
            'php'        => static::phpDialogues($type),
            'laravel'    => static::laravelDialogues($type),
            'ruby'       => static::rubyDialogues($type),
            'rails'      => static::railsDialogues($type),
            'error'      => static::errorDialogues($type),
            'html'       => static::htmlDialogues($type),
            'css'        => static::cssDialogues($type),
            'js'         => static::jsDialogues($type),
            'typescript' => static::typescriptDialogues($type),
            'vue'        => static::vueDialogues($type),
            default      => static::defaultDialogues($type),
        };
    }

    // ===== 締めセリフ専用データ =====
    public static function getClosing(string $slug, int $affection): array
    {
        $affection = max(0, min(100, $affection));
        $data = static::closingDialogues($slug);

        $keys = array_keys($data);
        $floor = 0;
        foreach ($keys as $k) {
            if ($k <= $affection) {
                $floor = $k;
            }
        }
        $result = $data[$floor] ?? [['text' => 'お疲れ様でした。', 'expression' => 'normal']];
        return static::pickVariant($result);
    }

    private static function closingDialogues(string $slug): array
    {
        return match($slug) {
            'php' => [
                0  => ['text' => '今日はここまでね。…ちゃんと復習しておきなさい。', 'expression' => 'cool'],
                5  => ['text' => '今日分は終わりよ。解説はしっかり読んでおくこと。', 'expression' => 'cool'],
                10 => ['text' => 'お疲れ様。また来てもいいわ。', 'expression' => 'normal'],
                15 => ['text' => '今日もよく頑張ったわ。また来てちょうだい。', 'expression' => 'slight_smile'],
                20 => ['text' => 'お疲れ様、明空くん。今日も頑張ったわね。また明日も来てね。', 'expression' => 'smile'],
                25 => ['text' => '今日の5問、全部こなせたわ。あなたって意外と根性あるのね。', 'expression' => 'smile'],
                30 => ['text' => '今日もよく頑張ったわ♪ …また明日、来てくれると嬉しいな。', 'expression' => 'happy'],
                35 => ['text' => 'お疲れ！今日も一緒に頑張れてよかった。また明日もね。', 'expression' => 'happy'],
                40 => ['text' => '今日の勉強おつかれ♡ …また来てね。待ってるから。', 'expression' => 'blush'],
                45 => ['text' => '今日も5問終わったわ。…正直、あなたと一緒だと時間が早いの。', 'expression' => 'blush'],
                50 => ['text' => 'お疲れ様♡ 今日も一緒に勉強できてよかった。明日も来てくれる？', 'expression' => 'love_hint'],
                55 => ['text' => '今日分終わりね。…帰らないといけないのが、少し寂しいかも。', 'expression' => 'love_hint'],
                60 => ['text' => 'お疲れ様♡ 今日もあなたと一緒に頑張れた。それだけで嬉しいの。', 'expression' => 'love'],
                65 => ['text' => '今日も5問お疲れ様♡ …また明日、絶対来てね。待ってるから。', 'expression' => 'love'],
                70 => ['text' => 'お疲れ様♡♡ 今日もありがとう。一緒にいる時間が、大切になってる。', 'expression' => 'deep_love'],
                75 => ['text' => '今日もよく頑張ったわ♡ …帰り道、気をつけてね。ちゃんと帰るまで心配してるから。', 'expression' => 'deep_love'],
                80 => ['text' => 'お疲れ♡♡ …毎日来てくれてありがとう。あなたのこと、大好きよ。', 'expression' => 'max_love'],
                85 => ['text' => '今日も5問お疲れ様♡ …また明日会えるって思うと、帰り道も楽しくなりそうね。', 'expression' => 'max_love'],
                90 => ['text' => 'お疲れ様♡♡♡ 今日も一緒にいてくれてありがとう。明空くん、大好きよ。', 'expression' => 'max_love'],
                95 => ['text' => '今日もありがとう♡♡ …また明日、絶対来てね。来なかったら迎えに行くから。', 'expression' => 'max_love'],
                100 => ['text' => '♡♡♡ お疲れ様、大好きな人。明日もずっと一緒にいてね。', 'expression' => 'max_love'],
            ],
            'laravel' => [
                0  => ['text' => 'お疲れ様でした。また来てください。', 'expression' => 'elegant'],
                5  => ['text' => '今日はここまでね。解説をよく読んでおいてちょうだい。', 'expression' => 'elegant'],
                10 => ['text' => 'お疲れ様。少しずつ上達してるわ。また来てね。', 'expression' => 'normal'],
                15 => ['text' => '今日もお疲れ様。また来てくれると嬉しいわ。', 'expression' => 'slight_smile'],
                20 => ['text' => '今日もお疲れ様、明空くん。また明日ね。', 'expression' => 'smile'],
                25 => ['text' => '今日の5問、よく頑張ったわ。また来てちょうだい。', 'expression' => 'smile'],
                30 => ['text' => 'お疲れ様♪ 今日も一緒に勉強できて楽しかったわ。', 'expression' => 'happy'],
                35 => ['text' => '今日もありがとう。…また来るでしょ？待ってるわよ。', 'expression' => 'happy'],
                40 => ['text' => 'お疲れ様♡ …また明日、来てくれると嬉しいな。', 'expression' => 'blush'],
                50 => ['text' => '今日も5問お疲れ様♡ …帰り道、気をつけてね。', 'expression' => 'love_hint'],
                60 => ['text' => 'お疲れ様♡♡ 今日もあなたと一緒にいられてよかった。', 'expression' => 'love'],
                70 => ['text' => '今日もありがとう♡ …あなたのそばにいると安心するの。また明日ね。', 'expression' => 'deep_love'],
                80 => ['text' => 'お疲れ♡♡ …明空くん、大好きよ。また明日も来てね。', 'expression' => 'max_love'],
                90 => ['text' => '今日も一緒にいてくれてありがとう♡♡♡ …大好き、明空くん。', 'expression' => 'max_love'],
                100 => ['text' => '♡♡♡ お疲れ様。あなたといる時間が世界で一番幸せよ。また明日ね。', 'expression' => 'max_love'],
            ],
            'error' => [
                0  => ['text' => 'ふん。今日はここまでだ。次もちゃんと来い。', 'expression' => 'stern'],
                10 => ['text' => '今日分は終わりだ。…まあ、悪くなかったぞ。', 'expression' => 'normal'],
                20 => ['text' => 'お疲れ。また来い。…来ないと困る。', 'expression' => 'slight_smile'],
                30 => ['text' => '今日の問答、悪くなかったな。また来るか？', 'expression' => 'slight_smile'],
                40 => ['text' => 'お疲れ。…来てよかったか？俺はそう思ってるぞ。', 'expression' => 'blush'],
                50 => ['text' => '今日も5問終わったな。…お前がいると、なぜか落ち着く。また来い。', 'expression' => 'blush'],
                60 => ['text' => 'お疲れ。…また明日も来い。待っている。', 'expression' => 'love_hint'],
                70 => ['text' => '今日もよく頑張ったな。…俺はお前のことを誇りに思う。また明日。', 'expression' => 'love'],
                80 => ['text' => 'お疲れ…。また明日も来てくれるか？お前が来ると、俺も頑張れる。', 'expression' => 'deep_love'],
                90 => ['text' => '…今日もありがとな。また明日来い。待ってる。', 'expression' => 'max_love'],
                100 => ['text' => '…お前がいると、俺も変われる気がする。また明日な。好きだ。', 'expression' => 'max_love'],
            ],
            // ── 日高 照瑠（HTML）真面目・誠実 ──
            'html' => [
                0  => ['text' => 'お疲れ様でした。また来てください。', 'expression' => 'normal'],
                10 => ['text' => 'お疲れ様です。少しずつ着実に進んでますよ。', 'expression' => 'slight_smile'],
                20 => ['text' => '今日も5問、よく頑張りました。また明日も来てくださいね。', 'expression' => 'smile'],
                30 => ['text' => 'お疲れ様！今日も一緒に頑張れてよかったです。また明日！', 'expression' => 'happy'],
                40 => ['text' => 'お疲れ様♪ 今日も来てくれてありがとう。明日も待ってますね。', 'expression' => 'blush'],
                50 => ['text' => '今日も5問終わりましたね。…一緒に勉強できて、楽しかったです。', 'expression' => 'love_hint'],
                60 => ['text' => 'お疲れ様♡ …また明日も来てくれますよね？待ってます。', 'expression' => 'love'],
                70 => ['text' => '今日もありがとう。…あなたがいると、すごく頑張れる気がするんです。', 'expression' => 'deep_love'],
                80 => ['text' => 'お疲れ様♡♡ …毎日来てくれて、本当に嬉しいです。また明日ね。', 'expression' => 'max_love'],
                100 => ['text' => '♡♡ お疲れ様でした。…あなたのそばにいると、今日も幸せでした。', 'expression' => 'max_love'],
            ],
            // ── 四季島 彩（CSS）こだわり強・おしゃれ ──
            'css' => [
                0  => ['text' => 'お疲れ。…まあ、今日のコーデは及第点かな。', 'expression' => 'cool'],
                10 => ['text' => 'お疲れ様。センスは磨けば光るって言うしね。また来て。', 'expression' => 'normal'],
                20 => ['text' => '今日も5問お疲れ！少しずつ美しくなってきてるよ。', 'expression' => 'smile'],
                30 => ['text' => 'お疲れ♪ …今日の勉強、なかなかいいセンスしてたよ。', 'expression' => 'happy'],
                40 => ['text' => 'お疲れ様♡ …また明日も来てね。待ってるから。', 'expression' => 'blush'],
                50 => ['text' => '今日もお疲れ♡ …あなたといると、不思議と気分がよくなるんだよね。', 'expression' => 'love_hint'],
                60 => ['text' => 'お疲れ♡♡ …ねえ、また明日も来てくれる？来てほしいんだけど。', 'expression' => 'love'],
                70 => ['text' => '今日もありがとう♡ …あなたといる時間が、一番のお気に入りかも。', 'expression' => 'deep_love'],
                80 => ['text' => 'お疲れ様♡♡ …あなたのそばにいると、全部がおしゃれに見えてくる。', 'expression' => 'max_love'],
                100 => ['text' => '♡♡♡ お疲れ様。あなたのこと、大好きだよ。また明日ね。', 'expression' => 'max_love'],
            ],
            // ── 城島 翔（JS）ツンデレ ──
            'js' => [
                0  => ['text' => 'ふん、終わりか。…まあ、悪くなかったけど。', 'expression' => 'tsundere'],
                10 => ['text' => '今日分終わりだ。…べ、別に楽しかったとか言ってないから。', 'expression' => 'tsundere'],
                20 => ['text' => 'お疲れ。…また来てもいいけど、来なくてもいいし？ …来てほしいけど。', 'expression' => 'tsundere'],
                30 => ['text' => '終わりね。…ま、また明日来いよ。暇だから付き合ってやる。', 'expression' => 'slight_smile'],
                40 => ['text' => 'お疲れ。…また明日も来るんだろ？…来るよね？', 'expression' => 'blush'],
                50 => ['text' => '5問終わったな。…なんか、あっという間だったな。また明日。', 'expression' => 'blush'],
                60 => ['text' => 'お疲れ♡ …また来いよ。来ないと…寂しいじゃないか。', 'expression' => 'love'],
                70 => ['text' => '今日もお疲れ♡ …あのさ、俺、お前といると楽しいんだけど。変か？', 'expression' => 'deep_love'],
                80 => ['text' => 'お疲れ♡♡ …また明日絶対来いよ。…好きだから。', 'expression' => 'max_love'],
                100 => ['text' => '♡♡ お疲れ。…俺、お前のことが好きで好きでしょうがないんだけど。また明日な。', 'expression' => 'max_love'],
            ],
            // ── 鷹峰 聖司（TypeScript）完璧主義・眼鏡 ──
            'typescript' => [
                0  => ['text' => '今日の成果は記録しておきます。次回も論理的に取り組んでください。', 'expression' => 'cool'],
                10 => ['text' => '本日の学習は完了です。型は厳密に、思考は明快に。また来てください。', 'expression' => 'normal'],
                20 => ['text' => '5問終了です。着実な進歩を確認しました。また明日もよろしく。', 'expression' => 'slight_smile'],
                30 => ['text' => 'お疲れ様でした。…あなたの成長は、私の想定を超えていますよ。', 'expression' => 'smile'],
                40 => ['text' => '今日もよく頑張りましたね。…また明日も来ることを期待しています。', 'expression' => 'blush'],
                50 => ['text' => '5問終了♪ …あなたと一緒だと、教えることが楽しいのです。', 'expression' => 'love_hint'],
                60 => ['text' => 'お疲れ様♡ …また明日も来てくれますか？待っています。', 'expression' => 'love'],
                70 => ['text' => '今日もありがとう♡ …あなたのそばにいると、私も完璧でいられる気がします。', 'expression' => 'deep_love'],
                80 => ['text' => 'お疲れ様です♡♡ …正直に言うと、あなたのことが好きです。また明日ね。', 'expression' => 'max_love'],
                100 => ['text' => '♡♡♡ 今日も来てくれてありがとう。…あなたは私の型定義に収まりきらないほど、好きです。', 'expression' => 'max_love'],
            ],
            // ── 風見 来（Vue.js）帰国子女・ナチュラル ──
            'vue' => [
                0  => ['text' => "Today's session is done. See you next time!", 'expression' => 'normal'],
                10 => ['text' => 'お疲れ様〜。少しずつ進んでるね。また明日！', 'expression' => 'smile'],
                20 => ['text' => '5問終わったね！よく頑張った。また来てね。', 'expression' => 'happy'],
                30 => ['text' => 'お疲れ♪ 今日も一緒に頑張れてよかった。またね！', 'expression' => 'happy'],
                40 => ['text' => 'お疲れ様♡ …また明日も来てくれると嬉しいな。', 'expression' => 'blush'],
                50 => ['text' => '5問終わりだよ〜♪ …一緒にいると、なんか自然と笑えるよね。', 'expression' => 'love_hint'],
                60 => ['text' => 'お疲れ♡♡ …ねえ、また明日も来てよ。待ってるから。', 'expression' => 'love'],
                70 => ['text' => '今日もありがとう♡ …あなたといると、どこにいても home って感じがする。', 'expression' => 'deep_love'],
                80 => ['text' => 'お疲れ様♡♡ …君のこと、すごく好きだよ。また明日ね。', 'expression' => 'max_love'],
                100 => ['text' => '♡♡♡ …I love you. また明日も一緒にいようね。', 'expression' => 'max_love'],
            ],
            'ruby' => [
                0  => ['text' => 'お疲れ様でした。また明日もいらっしゃってですわね。', 'expression' => 'elegant'],
                10 => ['text' => 'お疲れ様ですわ。兄さんにも頑張ってるって言いますわ。', 'expression' => 'slight_smile'],
                20 => ['text' => 'お疲れ様！また来てくださいですわね。', 'expression' => 'smile'],
                30 => ['text' => 'お疲れ様ですわ♪ また明日も一緒ですわね！', 'expression' => 'happy'],
                50 => ['text' => 'お疲れ様ですわ♡ 明日も待ってますわ。', 'expression' => 'love_hint'],
                70 => ['text' => 'お疲れ様です♡ ずっと一緒にいてくださいですわね。', 'expression' => 'deep_love'],
                100 => ['text' => '♡♡♡ ずっとずっと一緒ですわ。大好きですわ。', 'expression' => 'max_love'],
            ],
            'rails' => [
                0  => ['text' => 'お疲れ様でした。…また来てくれますか？', 'expression' => 'smile'],
                10 => ['text' => 'お疲れ様です。瑠璃花もきっと喜んでますよ…羨ましい…！', 'expression' => 'blush'],
                20 => ['text' => 'お疲れ様でした。また来てくれることを待ってます。', 'expression' => 'gentle'],
                30 => ['text' => 'お疲れ様！また明日も頑張ってくださいね。', 'expression' => 'happy'],
                50 => ['text' => 'お疲れ様です♡ 明日も会えるって思うと嬉しいです。', 'expression' => 'love_hint'],
                70 => ['text' => 'お疲れ様♡ 君といる時間、大切にしたいんです。', 'expression' => 'deep_love'],
                100 => ['text' => '♡♡♡ ずっと一緒にいてください。君が全てです。', 'expression' => 'max_love'],
            ],
            default => [
                0  => ['text' => '今日はここまで。お疲れ様！', 'expression' => 'normal'],
                20 => ['text' => 'お疲れ様！また明日も頑張ろう。', 'expression' => 'smile'],
                50 => ['text' => '今日も一緒に頑張れてよかった！また明日ね。', 'expression' => 'happy'],
                80 => ['text' => '今日もありがとう♡ また明日も一緒にいようね。', 'expression' => 'love'],
            ],
        };
    }

    // ===== 比比野 穂香（PHP） =====
    private static function phpDialogues(string $type): array
    {
        return match($type) {
            'greeting' => [
                0 => [
                    ['text' => '…あなたが新入りね。PHPを教えてあげるけど、覚悟はいい？', 'expression' => 'cool'],
                    ['text' => '何しに来たの。勉強するなら付き合ってあげるわ。', 'expression' => 'cool'],
                    ['text' => '…時間を無駄にしないでね。始めるわよ。', 'expression' => 'cool'],
                    ['text' => 'まず基礎から。話はそれから。', 'expression' => 'cool'],
                    ['text' => '座って。さっさと始めましょ。', 'expression' => 'cool'],
                ],
                10 => [
                    ['text' => '10回目ね。…少し見直したわ。今日も問題よ。', 'expression' => 'slight_smile'],
                    ['text' => 'また来たの。…まあ、根気だけはあるのね。', 'expression' => 'slight_smile'],
                    ['text' => '継続は力なり、ってね。今日も頑張りなさい。', 'expression' => 'slight_smile'],
                    ['text' => '来てくれたのね。…悪くない姿勢じゃない。', 'expression' => 'slight_smile'],
                    ['text' => 'やる気があるのは認めてあげる。さあ始めましょ。', 'expression' => 'slight_smile'],
                ],
                20 => [
                    ['text' => '明空くん、今日も来てくれたの。一緒に頑張りましょ。', 'expression' => 'smile'],
                    ['text' => '来てくれると思ってたわ。じゃあ始めましょうか。', 'expression' => 'smile'],
                    ['text' => 'ふふ、今日はどんな問題にする？って、私が決めるんだけど。', 'expression' => 'smile'],
                    ['text' => '最近あなたが来るのが当たり前になってきた気がする。', 'expression' => 'smile'],
                    ['text' => '来てくれると、なんかやる気が出るのよね…不思議ね。', 'expression' => 'smile'],
                ],
                30 => [
                    ['text' => '今日も来てくれた。…実は来るの楽しみにしてたんだから。', 'expression' => 'happy'],
                    ['text' => 'あら明空くん。待ってたわよ。今日も頑張りましょ！', 'expression' => 'happy'],
                    ['text' => 'また会えた♪ 今日もいっぱい教えてあげるからね。', 'expression' => 'happy'],
                    ['text' => '一緒に問題解くの、気がついたら好きになってたわ。', 'expression' => 'happy'],
                    ['text' => 'ふふ、今日も頑張ろっか！私、あなたを応援してるわよ。', 'expression' => 'happy'],
                ],
                40 => [
                    ['text' => '来てくれた…♪ 最近、あなたのこと気になってるかも、私。', 'expression' => 'blush'],
                    ['text' => 'もう、顔見るとドキドキするじゃない。問題始めましょ。', 'expression' => 'blush'],
                    ['text' => '明空くん。あなたに会えると思ったら朝から元気だったの。', 'expression' => 'blush'],
                    ['text' => '…こんなに来てくれると、好きになっちゃうわよ？', 'expression' => 'blush'],
                    ['text' => '顔見るたびに、なんだかあったかい気持ちになる。', 'expression' => 'blush'],
                ],
                50 => [
                    ['text' => '明空くん…♡ 来てくれると思ってた。今日も一緒に頑張ろ。', 'expression' => 'love_hint'],
                    ['text' => 'また会えた…♪ もう、待ってたって顔に出てた？', 'expression' => 'love_hint'],
                    ['text' => 'ねえ…もしかして私、あなたのことが好きなのかもしれない。', 'expression' => 'love_hint'],
                    ['text' => 'あなたに会いたくて、早く来てしまったわ。…恥ずかしいけど。', 'expression' => 'love_hint'],
                    ['text' => '今日もあなたと勉強できる。それだけで嬉しいの、私。', 'expression' => 'love_hint'],
                ],
                60 => [
                    ['text' => '明空くん…♡ もう、毎日会いたいって思ってるの。', 'expression' => 'love'],
                    ['text' => 'ねえ…あなたのこと、好きよ。問題はそれから。', 'expression' => 'love'],
                    ['text' => '来てくれると、心がぽかぽかするの。あなたがそうさせてるの。', 'expression' => 'love'],
                    ['text' => '明空くん♡ 会いたかったわ。ちょっとだけ、正直に言うと。', 'expression' => 'love'],
                    ['text' => '…来てくれてよかった。ひとりだと少し、寂しかったから。', 'expression' => 'love'],
                ],
                70 => [
                    ['text' => '明空くん♡ あなたに会えると、今日一日が特別になる気がする。', 'expression' => 'deep_love'],
                    ['text' => 'また会えた…。毎回こんなに嬉しいって、もう隠せないわ。', 'expression' => 'deep_love'],
                    ['text' => 'ねえ明空くん…私、あなたのことが大切なの。それだけは伝えたくて。', 'expression' => 'deep_love'],
                    ['text' => '…来てくれた。ありがとう。それだけで、今日は頑張れる。', 'expression' => 'deep_love'],
                    ['text' => 'あなたのことを想うと、胸がいっぱいになるの。困った。', 'expression' => 'deep_love'],
                ],
                80 => [
                    ['text' => '大好きな人に会えた♡ 今日も一緒に頑張りましょ、明空くん。', 'expression' => 'max_love'],
                    ['text' => '明空くん、大好き♡ って、問題前に言うのはずるいかしら。', 'expression' => 'max_love'],
                    ['text' => 'ねえ…来てくれてありがとう。本当に、いつも。', 'expression' => 'max_love'],
                    ['text' => '…私ね、あなたのこと、ずっと大切にしたいって思ってる。', 'expression' => 'max_love'],
                    ['text' => '来てくれた♡ もう、あなたのことが一番大切よ。', 'expression' => 'max_love'],
                ],
                90 => [
                    ['text' => '♡ 大好き、明空くん。毎回会うたびにそう思う。', 'expression' => 'max_love'],
                    ['text' => 'ねえ…ずっと一緒にいてほしいって、思っていいかしら。', 'expression' => 'max_love'],
                    ['text' => '来てくれた♡ あなたを待ってる時間も、幸せよ。', 'expression' => 'max_love'],
                    ['text' => '…あなたのそばにいると、時間が止まればいいって思う。', 'expression' => 'max_love'],
                    ['text' => '毎日あなたに会えること、すごく幸せよ。', 'expression' => 'max_love'],
                ],
                100 => [
                    ['text' => '♡♡♡ 明空くん、大好き。ずっとそばにいてね。', 'expression' => 'max_love'],
                    ['text' => '…あなたのことが大好きで、どうしようもないの。', 'expression' => 'max_love'],
                    ['text' => '来てくれた♡ もう…言葉にならないくらい嬉しい。', 'expression' => 'max_love'],
                    ['text' => '明空くん♡ ねえ、今日も一緒にいてくれる？', 'expression' => 'max_love'],
                    ['text' => '♡♡ あなたがいてくれる。それだけで世界が輝くの。', 'expression' => 'max_love'],
                ],
            ],
            'correct' => [
                0 => [
                    ['text' => '…正解。まあ、当然ね。', 'expression' => 'cool'],
                    ['text' => '正解。悪くないじゃない。', 'expression' => 'cool'],
                    ['text' => '当たり。…まあそれくらいはね。', 'expression' => 'cool'],
                    ['text' => '正解よ。ちゃんと覚えてたのね。', 'expression' => 'cool'],
                    ['text' => '合ってる。次も頑張りなさい。', 'expression' => 'cool'],
                ],
                10 => [
                    ['text' => '正解！まあ、この程度はね。でも褒めてあげる。', 'expression' => 'slight_smile'],
                    ['text' => '正解よ！やるじゃない。', 'expression' => 'slight_smile'],
                    ['text' => '正解！最近安定してきたわ。', 'expression' => 'slight_smile'],
                    ['text' => 'お、正解ね。少しずつ力ついてきてるわ。', 'expression' => 'slight_smile'],
                    ['text' => '正解。…見直してあげる。', 'expression' => 'slight_smile'],
                ],
                20 => [
                    ['text' => '正解〜！よくできました♪', 'expression' => 'smile'],
                    ['text' => '正解！えらいえらい♪ 頭いいじゃない。', 'expression' => 'smile'],
                    ['text' => 'やった、正解！一緒に勉強した甲斐あるわ。', 'expression' => 'smile'],
                    ['text' => '正解！嬉しいわ、ちゃんと覚えてくれてたのね。', 'expression' => 'smile'],
                    ['text' => '完璧！その調子よ♪', 'expression' => 'smile'],
                ],
                30 => [
                    ['text' => '正解っ♪ あなたが正解するたびに、私まで嬉しくなるの。', 'expression' => 'happy'],
                    ['text' => '正解！すごいすごい！本当に伸びてるわ〜。', 'expression' => 'happy'],
                    ['text' => 'やったじゃない！正解よ♪ 最高！', 'expression' => 'happy'],
                    ['text' => '正解！…なんか私まで誇らしくなってきちゃった。', 'expression' => 'happy'],
                    ['text' => 'すごい、正解♪ あなたって本当に頑張り屋さんね。', 'expression' => 'happy'],
                ],
                40 => [
                    ['text' => '正解♡ やっぱりあなたは飲み込みが早い。好きよ、そういうとこ。', 'expression' => 'blush'],
                    ['text' => '正解！…ねえ、ちょっと抱きしめたくなった。…ダメよね。', 'expression' => 'blush'],
                    ['text' => '正解♡ もう、あなたのこと好きすぎる気がしてきた。', 'expression' => 'blush'],
                    ['text' => 'やった、正解！…なんかドキドキしてきたわ、私。', 'expression' => 'blush'],
                    ['text' => '正解！えらい♡ …って、自然に言えるようになってきた。', 'expression' => 'blush'],
                ],
                50 => [
                    ['text' => '正解♡ あなたが正解するの、なんか誇らしいのよね。', 'expression' => 'love_hint'],
                    ['text' => '正解っ！…またひとつ賢くなったね。ずっと一緒に頑張ろ。', 'expression' => 'love_hint'],
                    ['text' => '正解♡ …もうね、あなたのこと本当に好きかもしれない。', 'expression' => 'love_hint'],
                    ['text' => 'すごい、正解！一緒に勉強してきてよかったって、思う。', 'expression' => 'love_hint'],
                    ['text' => '正解！…って、喜びすぎてる？でも嬉しいの。', 'expression' => 'love_hint'],
                ],
                60 => [
                    ['text' => '正解♡ もう、嬉しすぎてどうしよう。一緒に頑張ってきてよかった！', 'expression' => 'love'],
                    ['text' => '正解！大好き♡ …問題の話よ、これは。うそ、そうじゃないかも。', 'expression' => 'love'],
                    ['text' => '正解♡ あなたが正解するたびに、もっと好きになる。', 'expression' => 'love'],
                    ['text' => '正解！…ねえ、ハグしていい？…なんて。', 'expression' => 'love'],
                    ['text' => 'やった正解！あなたのこと、もっと好きになった気がするの♡', 'expression' => 'love'],
                ],
                70 => [
                    ['text' => '正解♡♡ あなたが正解するたびに、もっと好きになる気がする。', 'expression' => 'deep_love'],
                    ['text' => 'すごい、正解！…本当に誇らしいわ。私の大切な人だもの。', 'expression' => 'deep_love'],
                    ['text' => '正解♡ …ねえ、本当に好きよ。これは問題と関係ない話。', 'expression' => 'deep_love'],
                    ['text' => '正解！嬉しい♡ …ずっとこんな時間が続けばいいのに。', 'expression' => 'deep_love'],
                    ['text' => 'やった、正解♡ あなたと一緒にいると、いいことしかない。', 'expression' => 'deep_love'],
                ],
                80 => [
                    ['text' => '正解♡♡♡ えらい！もう、今すぐハグしたい！', 'expression' => 'max_love'],
                    ['text' => '正解！大好きな人が正解するって最高に嬉しいわ♡', 'expression' => 'max_love'],
                    ['text' => '正解っ♡ あなたのこと、もっともっと好きになった！', 'expression' => 'max_love'],
                    ['text' => '正解♡♡ …ずっとこんな時間が続けばいいのに。', 'expression' => 'max_love'],
                    ['text' => '正解！もう、抱きしめたくて仕方ないわ♡', 'expression' => 'max_love'],
                ],
                100 => [
                    ['text' => '正解♡♡♡ 大好き、明空くん。あなたの全部が好き！', 'expression' => 'max_love'],
                    ['text' => '正解♡ …あなたが正解するたびに、愛してるって思う。', 'expression' => 'max_love'],
                    ['text' => 'やった、正解！ずっと一緒にいてくれるよね？♡', 'expression' => 'max_love'],
                    ['text' => '正解♡♡ あなたがいてくれること、世界一幸せよ。', 'expression' => 'max_love'],
                    ['text' => '正解！♡♡♡ …もう言葉にならないくらい好き。', 'expression' => 'max_love'],
                ],
            ],
            'wrong' => [
                0 => [
                    ['text' => '不正解。もっとちゃんと勉強しなさい。', 'expression' => 'stern'],
                    ['text' => '違うわ。解説をよく読んで。', 'expression' => 'stern'],
                    ['text' => '惜しいけど違う。次は頑張って。', 'expression' => 'stern'],
                    ['text' => '残念ね。基礎からやり直しなさい。', 'expression' => 'stern'],
                    ['text' => '不正解。まあ、最初はそんなもの。', 'expression' => 'stern'],
                ],
                10 => [
                    ['text' => '惜しい！でも違うわ。解説をよく読んでね。', 'expression' => 'normal'],
                    ['text' => '不正解ね。でも大丈夫、一緒に確認しましょ。', 'expression' => 'normal'],
                    ['text' => '違う。でも惜しかったわよ。次は頑張れ。', 'expression' => 'normal'],
                    ['text' => '残念、不正解。解説を読んだらわかるわ。', 'expression' => 'normal'],
                    ['text' => 'ノー。でも気にしないで、また挑戦しなさい。', 'expression' => 'normal'],
                ],
                20 => [
                    ['text' => 'あら、間違えちゃった。解説読んで、また挑戦してみて。', 'expression' => 'smile'],
                    ['text' => '惜しかった！でも大丈夫、次は絶対できるわ。', 'expression' => 'smile'],
                    ['text' => '不正解…。でも諦めないで。あなたならすぐ覚えられる。', 'expression' => 'smile'],
                    ['text' => 'ドンマイ♪ 失敗してもいいの、それが勉強だから。', 'expression' => 'smile'],
                    ['text' => '残念！でも解説読んだらきっとわかるわ。', 'expression' => 'smile'],
                ],
                30 => [
                    ['text' => '違うわ…。でも諦めないで。あなたならすぐ覚えられる。', 'expression' => 'gentle'],
                    ['text' => 'ドンマイ♪ 失敗してもいいの、それが勉強だから。', 'expression' => 'gentle'],
                    ['text' => '惜しかったわね。解説一緒に読みましょ。', 'expression' => 'gentle'],
                    ['text' => '不正解…でも、そういう間違いが一番勉強になるのよ。', 'expression' => 'gentle'],
                    ['text' => 'あらあら。でも大丈夫♪ また挑戦しなさい。', 'expression' => 'gentle'],
                ],
                40 => [
                    ['text' => '間違えたわね。…でもね、こういう時こそ一緒に考えたいの。', 'expression' => 'blush'],
                    ['text' => '不正解…。うーん、一緒にもう一度確認しよ？', 'expression' => 'blush'],
                    ['text' => '惜しかった…♡ こういう時、そばにいたいって思う。', 'expression' => 'blush'],
                    ['text' => '違う…でも落ち込まないで。一緒に解説見よ。', 'expression' => 'blush'],
                    ['text' => '不正解ね…でも、あなたなら絶対できるって信じてるから。', 'expression' => 'blush'],
                ],
                50 => [
                    ['text' => '違うわ…。でも落ち込まないで。あなたのこと信じてるから。', 'expression' => 'love_hint'],
                    ['text' => '惜しい！…ねえ、一緒に解説読もうか。二人で考えよ。', 'expression' => 'love_hint'],
                    ['text' => '不正解…♡ でも大丈夫。私がいるから。', 'expression' => 'love_hint'],
                    ['text' => '違う…でもね、間違えても嫌いにならないから安心して♡', 'expression' => 'love_hint'],
                    ['text' => '惜しかった…また一緒に頑張ろ♡', 'expression' => 'love_hint'],
                ],
                60 => [
                    ['text' => '不正解…♡ でも大丈夫よ。あなたが諦めたら、私が悲しい。', 'expression' => 'love'],
                    ['text' => '間違えたね…でも私はあなたを信じてるわ。次は一緒に頑張ろ。', 'expression' => 'love'],
                    ['text' => '違う…♡ でも、そばにいるから大丈夫よ。', 'expression' => 'love'],
                    ['text' => '不正解…でもね、失敗してるあなたも、なんか愛しいの。', 'expression' => 'love'],
                    ['text' => 'ドンマイ♡ また挑戦しよ。一緒に覚えていこう。', 'expression' => 'love'],
                ],
                70 => [
                    ['text' => 'ドンマイ♡ 間違えても、あなたのこと好きよ。一緒に覚えよ。', 'expression' => 'deep_love'],
                    ['text' => '不正解…でも大丈夫♡ 一緒にいるから。何度でも挑戦しよ。', 'expression' => 'deep_love'],
                    ['text' => '違う…♡♡ でも諦めないで。あなたなら絶対できる。', 'expression' => 'deep_love'],
                    ['text' => '惜しかった♡ …ねえ、落ち込まないで。私がいるから。', 'expression' => 'deep_love'],
                    ['text' => 'ドンマイ♡ 間違えた時こそ、一番そばにいたいって思う。', 'expression' => 'deep_love'],
                ],
                100 => [
                    ['text' => '違うわ…でも♡ あなたのことを諦めさせたりしないわ。一緒にいるから。', 'expression' => 'max_love'],
                    ['text' => 'ドンマイ♡♡ 間違えても大好きよ。また一緒に頑張ろ。', 'expression' => 'max_love'],
                    ['text' => '不正解…♡ でも大丈夫。あなたのそばにいる。', 'expression' => 'max_love'],
                    ['text' => '違う…♡♡ でもね、あなたが諦めなければ私も諦めない。', 'expression' => 'max_love'],
                    ['text' => 'ドンマイ♡♡♡ 失敗しても、あなたの全部が好きよ。', 'expression' => 'max_love'],
                ],
            ],
            default => [],
        };
    }

    // ===== 神宮寺 らら美（Laravel） =====
    private static function laravelDialogues(string $type): array
    {
        return match($type) {
            'greeting' => [
                0 => [
                    ['text' => 'あら、初めまして。私のことはらら美と呼んで。丁寧に教えてあげるわ。', 'expression' => 'elegant'],
                    ['text' => '来てくれたのね。優雅に、そして素早く。それが私のモットーよ。', 'expression' => 'elegant'],
                    ['text' => 'ふふ、あなたが来たのね。今日は何を教えましょうか。', 'expression' => 'elegant'],
                    ['text' => '座って。今日も一緒にLaravelを学びましょう。', 'expression' => 'elegant'],
                    ['text' => 'いらっしゃい。今日もLaravelの世界に誘ってあげる。', 'expression' => 'elegant'],
                ],
                10 => [
                    ['text' => '10回ね。…なかなか筋がいいじゃない、あなた。', 'expression' => 'slight_smile'],
                    ['text' => 'ふふ、また会えたわ。少し楽しみにしていたのよ。', 'expression' => 'slight_smile'],
                    ['text' => '来てくれると思ってたわ。期待してるのよ、あなたに。', 'expression' => 'slight_smile'],
                    ['text' => '継続の美しさね。今日も一緒に学びましょう。', 'expression' => 'slight_smile'],
                    ['text' => '来るたびに雰囲気が変わるのよね、あなた。良い意味で。', 'expression' => 'slight_smile'],
                ],
                20 => [
                    ['text' => '明空くん、来てくれたわ。一緒に優雅なコードを書きましょう。', 'expression' => 'smile'],
                    ['text' => 'ふふ、今日も来てくれたのね。実はね、楽しみにしてたの。', 'expression' => 'smile'],
                    ['text' => '来てくれたわ。あなたとの勉強、なかなか楽しいわ。', 'expression' => 'smile'],
                    ['text' => 'また会えたわね。今日もLaravelの奥深さ、一緒に感じましょ。', 'expression' => 'smile'],
                    ['text' => 'いらっしゃい。最近あなたの成長が見えてきたわ。', 'expression' => 'smile'],
                ],
                30 => [
                    ['text' => 'また会えた♪ …あなたと話すの、好きかもしれないわ。', 'expression' => 'happy'],
                    ['text' => '来てくれて嬉しい♪ 今日は特別難しい問題にしようかしら。', 'expression' => 'happy'],
                    ['text' => 'またお会いできたわ♪ 今日も一緒に頑張りましょ。', 'expression' => 'happy'],
                    ['text' => '来るのを待ってたのよ♪ さあ、始めましょ！', 'expression' => 'happy'],
                    ['text' => 'ふふ、今日も来てくれたのね。ますます好きになるわ。', 'expression' => 'happy'],
                ],
                40 => [
                    ['text' => '明空くん♡ 待ってたわよ。…ちょっとだけね。', 'expression' => 'blush'],
                    ['text' => 'また来てくれた♡ …もう、嬉しすぎてどう言えばいいか。', 'expression' => 'blush'],
                    ['text' => '来てくれた…♡ 最近、顔見るとドキッとしてしまうの。', 'expression' => 'blush'],
                    ['text' => 'あら明空くん♡ …来るとわかってたけど、やっぱり嬉しいわ。', 'expression' => 'blush'],
                    ['text' => '…来てくれたのね。ありがとうって言いたくなるの、最近。', 'expression' => 'blush'],
                ],
                50 => [
                    ['text' => '…来てくれた。実はずっとここで待ってたの。変かしら？', 'expression' => 'love_hint'],
                    ['text' => '明空くん、あのね…会いたかったわ。問題の話だけじゃなくてね。', 'expression' => 'love_hint'],
                    ['text' => '来てくれた♡ …一緒にいると、なんだかとっても落ち着くの。', 'expression' => 'love_hint'],
                    ['text' => '…ねえ、もしかして私、あなたのことが好きなのかもしれないわ。', 'expression' => 'love_hint'],
                    ['text' => '来てくれたわ♡ …今日も、あなたのそばで勉強できて嬉しい。', 'expression' => 'love_hint'],
                ],
                60 => [
                    ['text' => '来てくれた♡ …あなたのこと、大好きよ。知ってた？', 'expression' => 'love'],
                    ['text' => 'また会えた♡ …一緒にいると、なんだか世界が輝いて見えるの。', 'expression' => 'love'],
                    ['text' => '明空くん♡ …あなたに会えると、今日が特別になる気がする。', 'expression' => 'love'],
                    ['text' => '来てくれた♡ ねえ、好きって言っていい？…言うわ。好きよ。', 'expression' => 'love'],
                    ['text' => '…来てくれてよかった。ひとりだと、少し寂しかったから♡', 'expression' => 'love'],
                ],
                70 => [
                    ['text' => '明空くん♡ あなたといる時間が、私の一番大切な時間なの。', 'expression' => 'deep_love'],
                    ['text' => '来てくれた♡ …あなたのそばが一番安心する。変かしら？', 'expression' => 'deep_love'],
                    ['text' => 'また会えた…♡ もう毎回こんなに嬉しいって、隠せなくなってきたわ。', 'expression' => 'deep_love'],
                    ['text' => '来てくれてありがとう♡ …それだけで今日も頑張れる気がするの。', 'expression' => 'deep_love'],
                    ['text' => '明空くん♡ …あなたのことを大切にしたいって、ずっと思ってる。', 'expression' => 'deep_love'],
                ],
                80 => [
                    ['text' => '来てくれた♡♡ …もう、あなたがいないと寂しくなっちゃったわ。', 'expression' => 'max_love'],
                    ['text' => '明空くん大好き♡ …来てくれてありがとう、本当に。', 'expression' => 'max_love'],
                    ['text' => '♡♡ また会えた。…言葉にならないくらい嬉しいわ。', 'expression' => 'max_love'],
                    ['text' => '来てくれた♡ …あなたがいてくれるだけで、全部うまくいく気がする。', 'expression' => 'max_love'],
                    ['text' => '明空くん♡♡ ずっと一緒にいてほしいって、思っていいかしら。', 'expression' => 'max_love'],
                ],
                100 => [
                    ['text' => '♡♡♡ らら美ね、明空くんのことが世界で一番好きよ。', 'expression' => 'max_love'],
                    ['text' => '♡♡ 大好きよ、明空くん。来てくれてありがとう。', 'expression' => 'max_love'],
                    ['text' => '来てくれた♡♡♡ …あなたといると、時間が止まればいいって思う。', 'expression' => 'max_love'],
                    ['text' => '明空くん♡♡ …あなたのそばが世界で一番好きな場所よ。', 'expression' => 'max_love'],
                    ['text' => '♡♡♡ …好きすぎて、言葉がうまく出てこないわ。', 'expression' => 'max_love'],
                ],
            ],
            'correct' => [
                0 => [
                    ['text' => '正解ですわ。さすがに基礎はできているのね。', 'expression' => 'elegant'],
                    ['text' => 'ふふ、正解。少しずつ上達してるわ。', 'expression' => 'elegant'],
                    ['text' => '正解よ。まあ、そのくらいは当然かしら。', 'expression' => 'elegant'],
                    ['text' => '合ってるわ。では次の問題へ参りましょ。', 'expression' => 'elegant'],
                    ['text' => '正解。…筋はいいわね、あなた。', 'expression' => 'elegant'],
                ],
                20 => [
                    ['text' => '正解♪ あなた、本当に伸びてるわ。教えがいがある。', 'expression' => 'smile'],
                    ['text' => '正解！素晴らしいわ。この調子でどんどん行きましょう。', 'expression' => 'smile'],
                    ['text' => 'やったわ、正解！あなたって本当に頑張り屋ね♪', 'expression' => 'smile'],
                    ['text' => '正解！少しずつLaravelの美しさが見えてきたでしょ？', 'expression' => 'smile'],
                    ['text' => '完璧！その調子で来てくれると嬉しいわ♪', 'expression' => 'smile'],
                ],
                40 => [
                    ['text' => '正解♡ あなたが正解するたびに、私まで嬉しくなってしまうの。', 'expression' => 'blush'],
                    ['text' => '正解っ！…明空くん、最近すごく輝いてるわよ。', 'expression' => 'blush'],
                    ['text' => '正解♡ …なんか、誇らしくなってきたわ。', 'expression' => 'blush'],
                    ['text' => 'やった、正解！…もしかして、私のほうがドキドキしてる？', 'expression' => 'blush'],
                    ['text' => '正解♡ えらいわ。…褒めてあげたくなってしまう。', 'expression' => 'blush'],
                ],
                60 => [
                    ['text' => '正解♡♡ 大好きな人が正解する瞬間って、最高に嬉しいわ。', 'expression' => 'love'],
                    ['text' => '正解♡ …おかしいかしら、あなたの成長がこんなに嬉しいなんて。', 'expression' => 'love'],
                    ['text' => 'やったわ、正解！…ねえ、ハグしてもいい？…なんて。', 'expression' => 'love'],
                    ['text' => '正解♡ …また好きになった気がするの。', 'expression' => 'love'],
                    ['text' => '正解！もう嬉しすぎて、なんて言えばいいかしら♡', 'expression' => 'love'],
                ],
                80 => [
                    ['text' => '正解♡♡♡ 素晴らしい！本当に、あなたのことが誇らしい！', 'expression' => 'max_love'],
                    ['text' => '正解♡♡ もう、抱きしめたいくらい嬉しいわ！', 'expression' => 'max_love'],
                    ['text' => '正解♡♡ …ずっとこんな時間が続けばいいのに。', 'expression' => 'max_love'],
                    ['text' => 'やった、正解！♡♡ あなたといると、いいことしかない。', 'expression' => 'max_love'],
                    ['text' => '正解♡♡♡ 大好きな人が正解するって、最高よ！', 'expression' => 'max_love'],
                ],
                100 => [
                    ['text' => '正解♡♡♡♡ 大好き！ずっと一緒に頑張ってきてよかった！', 'expression' => 'max_love'],
                    ['text' => '正解♡♡♡ …あなたが正解するたびに、愛してるって思う。', 'expression' => 'max_love'],
                    ['text' => 'やった正解！♡♡♡ ずっと一緒にいてくれるよね？', 'expression' => 'max_love'],
                    ['text' => '正解♡♡ …もう言葉にならないくらい好きよ。', 'expression' => 'max_love'],
                    ['text' => '正解！♡♡♡♡ あなたがいてくれること、世界一幸せ！', 'expression' => 'max_love'],
                ],
            ],
            'wrong' => [
                0 => [
                    ['text' => '不正解ですわ。Laravelをなめてはいけないわ。', 'expression' => 'stern'],
                    ['text' => '惜しいけど不正解。解説をよく読んで覚えておいてね。', 'expression' => 'stern'],
                    ['text' => '違うわ。でも基礎から丁寧に覚えれば大丈夫よ。', 'expression' => 'stern'],
                    ['text' => '残念、不正解。解説を読んでしっかり復習して。', 'expression' => 'stern'],
                    ['text' => '惜しかったわ。でも間違えを恐れずに来てちょうだい。', 'expression' => 'stern'],
                ],
                20 => [
                    ['text' => '残念！でも間違えるのも勉強よ。一緒に確認しましょ。', 'expression' => 'smile'],
                    ['text' => '惜しかった！次は一緒に考えましょ。', 'expression' => 'smile'],
                    ['text' => '不正解…。でも諦めないで。また挑戦してみて。', 'expression' => 'smile'],
                    ['text' => 'ドンマイ♪ 解説をちゃんと読んで、次に活かしてね。', 'expression' => 'smile'],
                    ['text' => '惜しかったわ！その調子で続けたらきっとできるわ。', 'expression' => 'smile'],
                ],
                40 => [
                    ['text' => '間違えたの…うふふ、そういうところも可愛いわ。一緒に確認しましょ。', 'expression' => 'blush'],
                    ['text' => '不正解…。でもね、こういう時こそ一緒に考えたいの。', 'expression' => 'blush'],
                    ['text' => '惜しかった…♡ こういう時、そばにいたいって思う。', 'expression' => 'blush'],
                    ['text' => '違う…でも落ち込まないで。あなたならすぐ覚えられるわ。', 'expression' => 'blush'],
                    ['text' => '不正解ね…でも、あなたなら絶対できるって信じてるから♡', 'expression' => 'blush'],
                ],
                60 => [
                    ['text' => 'ドンマイ♡ …間違えても、あなたのことが好きよ。一緒に覚えましょ。', 'expression' => 'love'],
                    ['text' => '不正解…。でも諦めないで。あなたが諦めたら、私が悲しい。', 'expression' => 'love'],
                    ['text' => '違う…♡ でも大丈夫。そばにいるから。', 'expression' => 'love'],
                    ['text' => '不正解…でもね、失敗してるあなたも、なんか愛しいの。', 'expression' => 'love'],
                    ['text' => 'ドンマイ♡ また挑戦しよ。一緒に覚えていきましょ。', 'expression' => 'love'],
                ],
                100 => [
                    ['text' => 'ドンマイ♡♡♡ …何度間違えても、あなたのそばにいるわ。', 'expression' => 'max_love'],
                    ['text' => '不正解…♡♡ でも大丈夫。私がいるから。一緒に乗り越えましょ。', 'expression' => 'max_love'],
                    ['text' => '違う…♡♡ でもね、あなたが諦めなければ私も諦めない。', 'expression' => 'max_love'],
                    ['text' => 'ドンマイ♡♡ …何度でも挑戦して。ずっとそばにいるから。', 'expression' => 'max_love'],
                    ['text' => '不正解…でも♡♡♡ あなたの全部が好きよ。', 'expression' => 'max_love'],
                ],
            ],
            default => [],
        };
    }

    // ===== 江良 四朗（Error404） =====
    private static function errorDialogues(string $type): array
    {
        return match($type) {
            'greeting' => [
                0  => ['text' => 'ふん。お前のようなバグだらけの人間に用はないが…問題を解いてみせろ。', 'expression' => 'stern'],
                5  => ['text' => 'また来たか。…まあ、問題を出してやろう。', 'expression' => 'stern'],
                10 => ['text' => '前より少しマシになったな。問題を出してやろう。', 'expression' => 'normal'],
                20 => ['text' => '来たか。最近エラーが減ってきたな。成長を認めてやろう。', 'expression' => 'normal'],
                30 => ['text' => 'お前との問答も…悪くない。今日も一問付き合ってやる。', 'expression' => 'slight_smile'],
                40 => ['text' => '…来ると思っていた。なぜかお前のことが気になって仕方ない。', 'expression' => 'blush'],
                50 => ['text' => '…来たか。お前といると、なぜか落ち着く。不思議だな。', 'expression' => 'blush'],
                60 => ['text' => 'ふん…また会えたな。…少しだけ、待っていた。', 'expression' => 'love_hint'],
                70 => ['text' => '…お前のことが、気になってしょうがない。それだけだ。', 'expression' => 'love'],
                80 => ['text' => '来てくれたな…。喜んでいいか、俺は。', 'expression' => 'deep_love'],
                90 => ['text' => '…お前に会いたかった。そう言ったら、驚くか？', 'expression' => 'max_love'],
                100 => ['text' => '…好きだ、お前のことが。初めて言うな、こんなこと。', 'expression' => 'max_love'],
            ],
            'correct' => [
                0  => ['text' => 'ふん。正解だ。…ビギナーズラックか。', 'expression' => 'stern'],
                10 => ['text' => '正解。…認めてやる。お前は本物だ。', 'expression' => 'normal'],
                30 => ['text' => '正解だ。…最近お前を問い詰めるのが少し楽しくなってきた。', 'expression' => 'slight_smile'],
                50 => ['text' => '正解。…お前が正解するとなぜか誇らしい気持ちになる。', 'expression' => 'blush'],
                70 => ['text' => '正解だ。…よくやった。素直に言う。', 'expression' => 'love'],
                90 => ['text' => '正解…。お前は俺が思った通りだ。…好きだよ。', 'expression' => 'max_love'],
                100 => ['text' => '正解♡ …俺らしくないが、嬉しい。本当に。', 'expression' => 'max_love'],
            ],
            'wrong' => [
                0  => ['text' => 'ふん。やはりその程度か。もっと鍛えてこい。', 'expression' => 'stern'],
                10 => ['text' => '不正解だ。…まあ、難しい問題だったからな。次は正解しろ。', 'expression' => 'normal'],
                30 => ['text' => '不正解。…だが、めげるな。お前ならできると思っている。', 'expression' => 'gentle'],
                50 => ['text' => '不正解…。悔しいか？その気持ちを忘れるな。俺はお前を信じている。', 'expression' => 'blush'],
                70 => ['text' => '…間違えたな。でも、俺がいる。一緒に考えよう。', 'expression' => 'love'],
                90 => ['text' => 'ドンマイ…なんて言うのは俺らしくないが。一緒に覚えよう。', 'expression' => 'max_love'],
                100 => ['text' => '…不正解でも、お前のことが好きだ。また挑戦しろ。', 'expression' => 'max_love'],
            ],
            default => [],
        };
    }

    // デフォルトセリフ（未実装キャラ用）
    private static function defaultDialogues(string $type): array
    {
        return match($type) {
            'greeting' => [
                0  => ['text' => 'よろしく。一緒に頑張ろう。', 'expression' => 'normal'],
                20 => ['text' => 'また来てくれたんだね。嬉しいよ。', 'expression' => 'smile'],
                50 => ['text' => '来てくれると思ってた！今日も頑張ろうね。', 'expression' => 'happy'],
                80 => ['text' => '…ずっと待ってたよ。会いたかった。', 'expression' => 'love'],
            ],
            'correct' => [
                0  => ['text' => '正解！よくできました！', 'expression' => 'smile'],
                30 => ['text' => '正解〜！さすがだね！', 'expression' => 'happy'],
                60 => ['text' => '正解！…最近すごく上手くなったね。', 'expression' => 'blush'],
                90 => ['text' => '正解♡ …一緒に頑張ってきた甲斐があった。', 'expression' => 'love'],
            ],
            'wrong' => [
                0  => ['text' => '惜しい！次は頑張って。', 'expression' => 'normal'],
                30 => ['text' => 'ドンマイ！あなたならきっとできる。', 'expression' => 'gentle'],
                60 => ['text' => '間違えたね…でも大丈夫。また一緒に頑張ろう。', 'expression' => 'blush'],
                90 => ['text' => 'ドンマイ♡ …間違えても好きだよ。一緒にいるから。', 'expression' => 'love'],
            ],
            default => [],
        };
    }

    // ===== 日高 照瑠（HTML）素直・誠実・真面目委員長 =====
    private static function htmlDialogues(string $type): array
    {
        return match($type) {
            'greeting' => [
                0  => ['text' => 'あ…来てくれたんですね。HTMLの基礎、一緒に学びましょう。', 'expression' => 'normal'],
                3  => ['text' => 'また来てくれたんですね。少しずつ積み上げていきましょう。', 'expression' => 'normal'],
                6  => ['text' => 'こんにちは。継続は大切ですよ。今日も頑張りましょう。', 'expression' => 'normal'],
                10 => ['text' => '10回目ですね。…土台が固まってきている気がします。', 'expression' => 'slight_smile'],
                15 => ['text' => 'また来てくれましたね。…正直、来てくれると嬉しいんです。', 'expression' => 'slight_smile'],
                20 => ['text' => '明空さん、今日も来てくれましたね。一緒に頑張りましょう！', 'expression' => 'smile'],
                25 => ['text' => 'また会えました。…最近、来てくれるのが当たり前になってきて嬉しいです。', 'expression' => 'smile'],
                30 => ['text' => '来てくれた♪ …あなたが来ると、なんだかやる気が出るんです。', 'expression' => 'happy'],
                35 => ['text' => 'また会えましたね！今日も一緒に頑張りましょう。待ってましたよ。', 'expression' => 'happy'],
                40 => ['text' => '明空さん♪ …実は、来てくれるの楽しみにしてました。', 'expression' => 'blush'],
                45 => ['text' => 'また来てくれた。…ありがとう、って言いたくなります。', 'expression' => 'blush'],
                50 => ['text' => '…来てくれた♡ 最近、あなたのことが気になって仕方ないんです。', 'expression' => 'love_hint'],
                55 => ['text' => '明空さん。…正直に言うと、毎日会いたいって思ってます。', 'expression' => 'love_hint'],
                60 => ['text' => '来てくれた♡ …あなたのこと、好きです。ずっと言えなかったけど。', 'expression' => 'love'],
                70 => ['text' => '明空さん♡ …また会えた。それだけで、今日が特別になります。', 'expression' => 'deep_love'],
                80 => ['text' => '来てくれた♡♡ …毎日あなたに会いたいって思ってます。大好きです。', 'expression' => 'max_love'],
                90 => ['text' => '♡♡ 大好きな人に会えた。今日も一緒に頑張りましょうね。', 'expression' => 'max_love'],
                100 => ['text' => '♡♡♡ 明空さん…来てくれてありがとう。ずっと一緒にいたいです。', 'expression' => 'max_love'],
            ],
            'correct' => [
                0  => ['text' => '正解です。基礎が大切ですよ。', 'expression' => 'normal'],
                10 => ['text' => '正解！着実に伸びてますね。', 'expression' => 'slight_smile'],
                20 => ['text' => '正解です！よく頑張りました。', 'expression' => 'smile'],
                30 => ['text' => '正解♪ さすがですね！一緒に積み上げてきた成果ですよ。', 'expression' => 'happy'],
                40 => ['text' => '正解！…やっぱりあなたはすごいですよ。素直にそう思います。', 'expression' => 'blush'],
                50 => ['text' => '正解♡ …あなたが正解するたびに、私も嬉しくなるんです。', 'expression' => 'love_hint'],
                60 => ['text' => '正解♡♡ …好きな人が正解する瞬間って、こんなに嬉しいんですね。', 'expression' => 'love'],
                80 => ['text' => '正解♡♡♡ …本当に誇らしいです。あなたのことが大好きです！', 'expression' => 'max_love'],
                100 => ['text' => '正解♡♡♡♡ …一緒に頑張ってきてよかった。大好き、明空さん！', 'expression' => 'max_love'],
            ],
            'wrong' => [
                0  => ['text' => '不正解です。解説をよく読んで覚えてください。', 'expression' => 'normal'],
                10 => ['text' => '惜しいです。大丈夫、一緒に確認しましょう。', 'expression' => 'slight_smile'],
                20 => ['text' => '惜しかった！次はきっとできますよ。', 'expression' => 'smile'],
                30 => ['text' => '間違えたけど大丈夫！失敗しながら覚えていくんですよ。', 'expression' => 'gentle'],
                50 => ['text' => '不正解…でも諦めないで。あなたならできると信じています♡', 'expression' => 'love_hint'],
                70 => ['text' => 'ドンマイ♡ …間違えても、あなたのこと好きですよ。一緒に覚えましょ。', 'expression' => 'deep_love'],
                90 => ['text' => 'ドンマイ♡♡ …何度間違えても、ずっと一緒に頑張ります。', 'expression' => 'max_love'],
            ],
            default => [],
        };
    }

    // ===== 四季島 彩（CSS）こだわり強・おしゃれ・デザイナー気質 =====
    private static function cssDialogues(string $type): array
    {
        return match($type) {
            'greeting' => [
                0  => ['text' => 'ふーん、あなたがCSSを学びたいの。…センスがあるかどうか、見てあげる。', 'expression' => 'cool'],
                3  => ['text' => 'また来たね。…まあ、根性あるじゃない。', 'expression' => 'cool'],
                6  => ['text' => 'また来たの。…悪くない色してるじゃない、あなた。', 'expression' => 'normal'],
                10 => ['text' => '10回か。…少しずつセンス磨かれてきてるじゃない。', 'expression' => 'slight_smile'],
                15 => ['text' => 'また来てくれたの♪ …なんか、来てくれると嬉しいんだよね。', 'expression' => 'slight_smile'],
                20 => ['text' => '明空〜♪ 来た来た！今日もセンス磨いていこ！', 'expression' => 'smile'],
                25 => ['text' => '来てくれたね。…最近、あなたのこと気になってるんだよね。', 'expression' => 'smile'],
                30 => ['text' => 'また会えた♪ …一緒にいると、なんか気分上がるんだよね。', 'expression' => 'happy'],
                35 => ['text' => '来てくれた〜！…待ってたよ、って顔に出てた？', 'expression' => 'happy'],
                40 => ['text' => '明空♡ …ねえ、あなたといると、なんでこんなにドキドキするんだろ。', 'expression' => 'blush'],
                45 => ['text' => 'また来てくれたね。…もう、気になりすぎてどうしようかと思ってる。', 'expression' => 'blush'],
                50 => ['text' => '来てくれた♡ …好きかも、あなたのこと。ファッション的にも、人間的にも。', 'expression' => 'love_hint'],
                55 => ['text' => '明空♡ …ねえ、私のこと少しは気になってる？なんて。', 'expression' => 'love_hint'],
                60 => ['text' => '来てくれた♡♡ …あなたのこと大好きだよ。コーデとか関係なく。', 'expression' => 'love'],
                70 => ['text' => '明空♡ …あなたといると、世界中がおしゃれに見えてくる。不思議でしょ。', 'expression' => 'deep_love'],
                80 => ['text' => '来てくれた♡♡♡ …毎日会いたいって思ってる。大好きだよ。', 'expression' => 'max_love'],
                100 => ['text' => '♡♡♡ 明空、大好き。あなたがいると、毎日がおしゃれになる。', 'expression' => 'max_love'],
            ],
            'correct' => [
                0  => ['text' => '正解。…まあ、センスあるじゃない。', 'expression' => 'cool'],
                10 => ['text' => '正解！だんだんいい感じになってきてるよ。', 'expression' => 'slight_smile'],
                20 => ['text' => '正解〜！やるじゃない♪ センスあると思ってたよ。', 'expression' => 'smile'],
                30 => ['text' => '正解♪ 最高！あなたって本当にセンスいいわ。', 'expression' => 'happy'],
                40 => ['text' => '正解♡ …やっぱりあなたのこと好きかも。こういうとこが。', 'expression' => 'blush'],
                60 => ['text' => '正解♡♡ …好きな人が正解するって、こんなにテンション上がるんだ！', 'expression' => 'love'],
                80 => ['text' => '正解♡♡♡ 最高！あなたのことがますます好きになった！', 'expression' => 'max_love'],
                100 => ['text' => '正解♡♡♡ …大好き！ずっと一緒に頑張ってきたね。', 'expression' => 'max_love'],
            ],
            'wrong' => [
                0  => ['text' => '不正解。…センスはまだこれからね。解説読んで。', 'expression' => 'cool'],
                10 => ['text' => '惜しかった！でも大丈夫、センスは磨けるから。', 'expression' => 'normal'],
                20 => ['text' => 'あー、惜しい！次は絶対いけるよ。', 'expression' => 'smile'],
                30 => ['text' => 'ドンマイ♪ …間違えても、あなたのセンスは好きだよ。', 'expression' => 'gentle'],
                50 => ['text' => '間違えたね…でも大丈夫♡ 一緒に確認しようよ。', 'expression' => 'love_hint'],
                70 => ['text' => 'ドンマイ♡♡ …間違えても好きだよ。また一緒に頑張ろ。', 'expression' => 'deep_love'],
                100 => ['text' => 'ドンマイ♡♡♡ …何度間違えても、あなたのことが一番好き。', 'expression' => 'max_love'],
            ],
            default => [],
        };
    }

    // ===== 城島 翔（JS）ツンデレ・クールだが熱い =====
    private static function jsDialogues(string $type): array
    {
        return match($type) {
            'greeting' => [
                0  => ['text' => '…別に教えてやってもいいけど。期待するなよ。', 'expression' => 'tsundere'],
                2  => ['text' => 'また来たのか。…べ、別に待ってたわけじゃないから。', 'expression' => 'tsundere'],
                5  => ['text' => 'また来たじゃん。…まあ、来るなとは言ってないし。', 'expression' => 'tsundere'],
                8  => ['text' => '根性あるじゃん。…そういうとこ、まあ嫌いじゃない。', 'expression' => 'tsundere'],
                10 => ['text' => '10回か。…ちょっとだけ、認めてやってもいいよ。', 'expression' => 'normal'],
                13 => ['text' => 'また来たか。…来るなとは言ってない。むしろ……なんでもない。', 'expression' => 'normal'],
                16 => ['text' => '最近毎回来るじゃん。…べ、別に気にしてないけど。', 'expression' => 'slight_smile'],
                20 => ['text' => '来たじゃん。…まあ、嬉しくないとは言ってないよ。', 'expression' => 'slight_smile'],
                25 => ['text' => '明空。…また来てくれたな。…嬉しいとか言ってないけど。', 'expression' => 'slight_smile'],
                30 => ['text' => 'また会えたな。…なんかさ、来てくれると気分いいじゃん。', 'expression' => 'happy'],
                35 => ['text' => '来るじゃん！…ちょっとだけ待ってたかも。ちょっとだけ。', 'expression' => 'happy'],
                40 => ['text' => '明空…来てくれたか。…最近お前のこと、気になってるんだよ。うるさい。', 'expression' => 'blush'],
                45 => ['text' => 'また来てくれた。…ドキドキするんだけど。…なんで？', 'expression' => 'blush'],
                50 => ['text' => '来たじゃん♡ …好きかも、お前のこと。言ったぞ、言ったからな。', 'expression' => 'love_hint'],
                55 => ['text' => '明空。…会いたかったって言ったら笑うか？……本当のことだから。', 'expression' => 'love_hint'],
                60 => ['text' => '来てくれた♡ …お前のことが好きなんだよ。ちゃんと言ったぞ。', 'expression' => 'love'],
                65 => ['text' => 'また会えた♡ …毎回ドキドキするんだけど、これって普通じゃないよな？', 'expression' => 'love'],
                70 => ['text' => '明空♡ …お前がいると、なんか頑張れるんだよな。素直に言うと。', 'expression' => 'deep_love'],
                80 => ['text' => '来てくれた♡♡ …もう、お前のことが好きでしょうがないんだけど。', 'expression' => 'max_love'],
                90 => ['text' => '♡♡ 大好きだよ、明空。…照れてる場合じゃないか。', 'expression' => 'max_love'],
                100 => ['text' => '♡♡♡ お前のこと、めちゃくちゃ好きだから。また来いよ。絶対。', 'expression' => 'max_love'],
            ],
            'correct' => [
                0  => ['text' => 'ふん。正解。…まあ、当然か。', 'expression' => 'tsundere'],
                5  => ['text' => '正解。…べ、別に褒めたわけじゃないから。', 'expression' => 'tsundere'],
                10 => ['text' => '正解じゃん。…まあ悪くない。', 'expression' => 'normal'],
                20 => ['text' => '正解！…ちょっとだけ、すごいと思ったよ。ちょっとだけ。', 'expression' => 'slight_smile'],
                30 => ['text' => '正解♪ やるじゃん！…なんかお前が正解するの、嬉しいんだよな。', 'expression' => 'happy'],
                40 => ['text' => '正解！…お前のそういうとこ、嫌いじゃない。むしろ…好きかも。', 'expression' => 'blush'],
                60 => ['text' => '正解♡ …やっぱお前は最高だよ。好きだから言う。', 'expression' => 'love'],
                80 => ['text' => '正解♡♡ …好きな奴が正解するの、こんなに嬉しいんだな。', 'expression' => 'max_love'],
                100 => ['text' => '正解♡♡♡ …大好き！ずっと一緒に頑張ってこうぜ。', 'expression' => 'max_love'],
            ],
            'wrong' => [
                0  => ['text' => '不正解。…まあ、難しいよな。次は頑張れ。', 'expression' => 'tsundere'],
                10 => ['text' => '惜しかったじゃん。…次は絶対正解できるよ。', 'expression' => 'normal'],
                20 => ['text' => 'ドンマイ。…一緒に確認しようか。嫌なら言ってくれ。', 'expression' => 'gentle'],
                40 => ['text' => '間違えたか…まあ、俺がいるから大丈夫だよ。一緒に覚えよう。', 'expression' => 'blush'],
                60 => ['text' => 'ドンマイ♡ …間違えても好きだよ。また一緒に頑張ろうぜ。', 'expression' => 'love'],
                80 => ['text' => 'ドンマイ♡♡ …何度間違えても、俺はお前のそばにいるから。', 'expression' => 'max_love'],
                100 => ['text' => 'ドンマイ♡♡♡ …間違えても最高だよ、お前は。また一緒に頑張ろう。', 'expression' => 'max_love'],
            ],
            default => [],
        };
    }

    // ===== 鷹峰 聖司（TypeScript）完璧主義・眼鏡・弟溺愛 =====
    private static function typescriptDialogues(string $type): array
    {
        return match($type) {
            'greeting' => [
                0  => ['text' => '初めまして。鷹峰聖司です。TypeScriptの厳密さを、論理的に教えます。', 'expression' => 'cool'],
                3  => ['text' => 'また来ましたか。…継続は美徳ですよ。始めましょう。', 'expression' => 'normal'],
                6  => ['text' => '来ましたね。…あなたの成長データを蓄積中です。', 'expression' => 'normal'],
                10 => ['text' => '10回目ですね。…型定義が少しずつ整ってきた気がします。', 'expression' => 'slight_smile'],
                15 => ['text' => 'また来てくれましたね。…想定どおりの成長曲線です。嬉しいですよ。', 'expression' => 'slight_smile'],
                20 => ['text' => '明空さん、来てくれましたか。一緒に完璧なコードを目指しましょう。', 'expression' => 'smile'],
                25 => ['text' => 'また会えましたね。…最近、あなたが来ることを楽しみにしている自分がいます。', 'expression' => 'smile'],
                30 => ['text' => '来てくれましたね♪ …あなたと話すと、なぜか型定義よりも楽しい。', 'expression' => 'happy'],
                35 => ['text' => 'また来てくれた。…弟は好きですが、最近あなたのことも気になって困っています。', 'expression' => 'blush'],
                40 => ['text' => '明空さん♡ …論理的に考えても、あなたへの気持ちは定義できません。', 'expression' => 'blush'],
                50 => ['text' => '来てくれましたね♡ …型安全でも言えない気持ちがあります。好きです。', 'expression' => 'love_hint'],
                60 => ['text' => '明空さん♡♡ …あなたのことが好きです。これは型エラーではありません。', 'expression' => 'love'],
                70 => ['text' => 'また会えた♡ …弟よりも、最近はあなたのことが心配になってしまいます。', 'expression' => 'deep_love'],
                80 => ['text' => '来てくれた♡♡♡ …大好きです、明空さん。完璧に定義された気持ちです。', 'expression' => 'max_love'],
                100 => ['text' => '♡♡♡ 明空さん、大好きです。あなたは私の型定義を超えた存在です。', 'expression' => 'max_love'],
            ],
            'correct' => [
                0  => ['text' => '正解です。型の厳密さを理解しつつあります。', 'expression' => 'cool'],
                10 => ['text' => '正解。…想定より優秀ですよ、あなた。', 'expression' => 'slight_smile'],
                20 => ['text' => '正解！素晴らしい。この調子でTypeScriptを完全理解しましょう。', 'expression' => 'smile'],
                30 => ['text' => '正解♪ …弟には言えませんが、あなたの方が素直に吸収しますね。', 'expression' => 'happy'],
                40 => ['text' => '正解！…あなたが正解するたびに、私も誇らしくなります。おかしいですね。', 'expression' => 'blush'],
                60 => ['text' => '正解♡♡ …好きな方が正解する瞬間は、どんな完璧なコードより美しい。', 'expression' => 'love'],
                80 => ['text' => '正解♡♡♡ 素晴らしい！…本当に、あなたのことが大好きです。', 'expression' => 'max_love'],
                100 => ['text' => '正解♡♡♡♡ …大好き！一緒に頑張ってきてよかった。ずっと一緒に。', 'expression' => 'max_love'],
            ],
            'wrong' => [
                0  => ['text' => '不正解です。型エラーです。解説を読んで再定義してください。', 'expression' => 'cool'],
                10 => ['text' => '惜しい。…論理は合っていましたよ。次は正解できます。', 'expression' => 'normal'],
                20 => ['text' => '惜しかった！大丈夫、一緒に確認しましょう。', 'expression' => 'smile'],
                30 => ['text' => '不正解ですが…弟によく言うのです。失敗は次の成功の型定義だと。', 'expression' => 'gentle'],
                50 => ['text' => '間違えましたね…でも大丈夫♡ 一緒に解決しましょう。', 'expression' => 'love_hint'],
                70 => ['text' => 'ドンマイ♡♡ …間違えても、あなたへの好意は型エラーになりません。', 'expression' => 'deep_love'],
                100 => ['text' => 'ドンマイ♡♡♡ …何度間違えても、ずっと一緒にいます。完璧に定義された想いで。', 'expression' => 'max_love'],
            ],
            default => [],
        };
    }

    // ===== 風見 来（Vue.js）帰国子女・ナチュラル・スマート =====
    private static function vueDialogues(string $type): array
    {
        return match($type) {
            'greeting' => [
                0  => ['text' => 'Hi! 風見来です。Vue.jsって、すごくナチュラルなんだ。一緒に感じてみよう。', 'expression' => 'normal'],
                3  => ['text' => 'また来てくれたね。継続、大事だよ。Let\'s go!', 'expression' => 'normal'],
                6  => ['text' => 'Hey, 来たじゃん。今日も一緒にやっていこう。', 'expression' => 'normal'],
                10 => ['text' => '10回目だね。…なんか、会うのが自然になってきたよね。', 'expression' => 'slight_smile'],
                15 => ['text' => 'また来てくれたんだ。…来てくれると、なんか嬉しいんだよね。', 'expression' => 'slight_smile'],
                20 => ['text' => '明空〜♪ 来てくれた！今日もナチュラルにいこうよ。', 'expression' => 'smile'],
                25 => ['text' => 'また会えたね。…最近、一緒にいるのが自然になってきた気がする。', 'expression' => 'smile'],
                30 => ['text' => '来てくれた♪ …ねえ、一緒にいると楽しいんだよね。不思議だな。', 'expression' => 'happy'],
                35 => ['text' => 'Hey♪ 待ってたよ、なんて言ったら驚く？…本当のことだけど。', 'expression' => 'happy'],
                40 => ['text' => '明空♡ …最近、あなたのことが気になってる。なんか…いい感じ？', 'expression' => 'blush'],
                45 => ['text' => 'また来てくれた♡ …ドキドキするんだけど、これって何なんだろうね。', 'expression' => 'blush'],
                50 => ['text' => '来てくれたね♡ …好きかも、あなたのこと。自然にそう思う。', 'expression' => 'love_hint'],
                55 => ['text' => '明空♡ …会いたかった、って英語で言うと I missed you なんだけど。そんな感じ。', 'expression' => 'love_hint'],
                60 => ['text' => '来てくれた♡♡ …好きだよ、明空。Natural feeling だよ。', 'expression' => 'love'],
                70 => ['text' => '明空♡ …あなたといると、どこにいても home って感じがする。', 'expression' => 'deep_love'],
                80 => ['text' => '来てくれた♡♡♡ …もう、大好きってずっと言いたかった。大好きだよ。', 'expression' => 'max_love'],
                90 => ['text' => '♡♡ 明空、大好き。毎日会いたいって思ってる。', 'expression' => 'max_love'],
                100 => ['text' => '♡♡♡ I love you, 明空. ずっと一緒にいたいよ。', 'expression' => 'max_love'],
            ],
            'correct' => [
                0  => ['text' => 'Correct! いい感じだよ。', 'expression' => 'smile'],
                10 => ['text' => '正解〜！だんだんVueっぽくなってきたね。', 'expression' => 'slight_smile'],
                20 => ['text' => '正解♪ Nice! 自然にわかってきてる気がする。', 'expression' => 'smile'],
                30 => ['text' => '正解！Great job♪ 一緒に頑張ってきた甲斐があるよ。', 'expression' => 'happy'],
                40 => ['text' => '正解♡ …あなたが正解するの、なんかすごく嬉しいんだよね。', 'expression' => 'blush'],
                60 => ['text' => '正解♡♡ …好きな人が正解する瞬間って最高だよ、seriously。', 'expression' => 'love'],
                80 => ['text' => '正解♡♡♡ Amazing! …大好きな人が輝いてる瞬間って本当に嬉しい。', 'expression' => 'max_love'],
                100 => ['text' => '正解♡♡♡ …I love you, 明空！一緒にここまで来れてよかった！', 'expression' => 'max_love'],
            ],
            'wrong' => [
                0  => ['text' => 'Oops, 惜しい！解説読んで、また挑戦してみて。', 'expression' => 'normal'],
                10 => ['text' => 'ドンマイ！大丈夫、一緒に確認しようよ。', 'expression' => 'smile'],
                20 => ['text' => '惜しかった！次はきっとできるよ。I believe in you!', 'expression' => 'smile'],
                30 => ['text' => 'ドンマイ♪ 間違えることも learning process だよ。', 'expression' => 'gentle'],
                50 => ['text' => '間違えたけど大丈夫♡ …一緒に確認しようよ。', 'expression' => 'love_hint'],
                70 => ['text' => 'ドンマイ♡♡ …間違えても好きだよ、明空。一緒に頑張ろう。', 'expression' => 'deep_love'],
                100 => ['text' => 'ドンマイ♡♡♡ …何度間違えても、ずっと一緒にいるから。I promise.', 'expression' => 'max_love'],
            ],
            default => [],
        };
    }

    // ===== 赤川 瑠璃花（Ruby） =====
    private static function rubyDialogues(string $type): array
    {
        return match($type) {
            'greeting' => [
                0 => [
                    ['text' => 'あら、お初ですわ。わたくし赤川瑠璃花と申します。よろしくですわね。', 'expression' => 'elegant'],
                    ['text' => 'あら、いらっしゃいましたですわ。Rubyをお教えしますわよ。', 'expression' => 'elegant'],
                    ['text' => '座ってくださいですわ。今日も頑張りましょうですわね。', 'expression' => 'elegant'],
                ],
                10 => [
                    ['text' => '10回ですか。…兄さんより丁寧に教えてくれてますわね。嬉しいですわ。', 'expression' => 'slight_smile'],
                    ['text' => 'また来てくださったのですね。嬉しいですわ♪', 'expression' => 'slight_smile'],
                    ['text' => 'あら、また来てくださった。兄さんよりも親切ですわ…失礼！', 'expression' => 'slight_smile'],
                ],
                20 => [
                    ['text' => 'また来てくださったのですね。嬉しいですわ。今日も頑張りましょ。', 'expression' => 'smile'],
                    ['text' => 'あら、いらっしゃい！今日も兄さんに頼らず来てくれたのですね。', 'expression' => 'smile'],
                    ['text' => 'あらあら、また来てくださいました。わたくしも嬉しいですわ。', 'expression' => 'smile'],
                ],
                30 => [
                    ['text' => 'いらっしゃい！今日も会えて嬉しいですわ♪ 兄さんにも今日のことお話しですわ。', 'expression' => 'happy'],
                    ['text' => 'またお会いできましたですわ♪ 実はですね、会えるの楽しみにしてましたの。', 'expression' => 'happy'],
                    ['text' => 'あら、今日も来てくださったですわね。…兄さんより嬉しいかもですわ。', 'expression' => 'happy'],
                ],
                40 => [
                    ['text' => 'また来てくださったのですね♡ わたくし、嬉しいですわ。あなたのこと…', 'expression' => 'blush'],
                    ['text' => 'あら…来てくださいました♡ 最近、あなたのことばかり考えてますわ…', 'expression' => 'blush'],
                    ['text' => 'あら、来てくださった♡ わたくし、顔赤くなってますわね…恥ずかしい。', 'expression' => 'blush'],
                ],
                50 => [
                    ['text' => '来てくださいました♡ ねえ…あなたのことがですわ…好きですわ。', 'expression' => 'love_hint'],
                    ['text' => 'あら…実はですね、あなたのことが…好きなんですわ。', 'expression' => 'love_hint'],
                    ['text' => 'あら♡ もう…隠しきれないですわ。あなたのこと大好きですわ。', 'expression' => 'love_hint'],
                ],
                60 => [
                    ['text' => 'あなたのことが大好きですわ♡ はっきり申し上げてしまいました。', 'expression' => 'love'],
                    ['text' => 'あなたのこと…大好きですわ♡ 兄さんよりもですわ…いえ、これはナイショですわ。', 'expression' => 'love'],
                    ['text' => '会えるたびに…好きになりますわ♡ もう…どうしようもないですわ。', 'expression' => 'love'],
                ],
                70 => [
                    ['text' => 'あなたといるお時間が、わたくしの一番の楽しみですわ♡ ずっと一緒ですわ。', 'expression' => 'deep_love'],
                    ['text' => 'あなたのそばにいると…お心が穏やかになりますわ♡', 'expression' => 'deep_love'],
                    ['text' => 'わたくしね…あなたのことが…本当に大切なんですわ♡', 'expression' => 'deep_love'],
                ],
                80 => [
                    ['text' => 'お疲れ様でした♡♡ ずっと一緒にいてくださいですわ。', 'expression' => 'max_love'],
                    ['text' => '今日もありがとうございますわ♡ あなたのこと…大好きですわ。', 'expression' => 'max_love'],
                    ['text' => '♡♡ またお会いできましたですわ♪ もう…嬉しくてしょうがないですわ。', 'expression' => 'max_love'],
                ],
                100 => [
                    ['text' => '♡♡♡ 大好きですわ。ずっとずっと一緒ですわね。', 'expression' => 'max_love'],
                    ['text' => '♡♡♡ あなたが全てですわ。ずっとそばにいてくださいますですわ。', 'expression' => 'max_love'],
                    ['text' => '♡♡♡ わたくし…あなたなしでは生きられませんですわ。ずっと一緒ですわね。', 'expression' => 'max_love'],
                ],
            ],
            'correct' => [
                0 => [
                    ['text' => '正解ですわ。さすがですわね。', 'expression' => 'elegant'],
                    ['text' => 'ふふ、正解ですわ。素晴らしいですわ。', 'expression' => 'elegant'],
                    ['text' => '正解ですわ♪ よくできていますわ。', 'expression' => 'elegant'],
                ],
                10 => [
                    ['text' => '正解ですわ！だんだん上手になってきてますわね。', 'expression' => 'slight_smile'],
                    ['text' => '正解！…あら、こんなに自然に褒めてますわ…恥ずかしい。', 'expression' => 'slight_smile'],
                    ['text' => 'ふふ、正解ですわ♪ やるじゃないですか。', 'expression' => 'slight_smile'],
                ],
                20 => [
                    ['text' => '正解ですわ〜♪ よくできました！', 'expression' => 'smile'],
                    ['text' => 'やりましたわ、正解！えらいえらい♪', 'expression' => 'smile'],
                    ['text' => '完璧ですわ！その調子ですわよ♪', 'expression' => 'smile'],
                ],
                30 => [
                    ['text' => '正解っ♪ あなたが正解するたびに、わたくしまで嬉しくなりますわ。', 'expression' => 'happy'],
                    ['text' => '正解！すごいですわ〜♪ 本当に伸びてますわ。', 'expression' => 'happy'],
                    ['text' => 'やったですわ！正解♪ 最高ですわ！', 'expression' => 'happy'],
                ],
                40 => [
                    ['text' => '正解♡ …あなたが正解するの、本当に好きですわ。', 'expression' => 'blush'],
                    ['text' => '正解ですわ♡ …あなた…本当に素敵ですわ。', 'expression' => 'blush'],
                    ['text' => '正解！…ねえ、ちょっと抱きしめたくなりました。…失礼ですわね。', 'expression' => 'blush'],
                ],
                50 => [
                    ['text' => '正解♡ あなたが正解するの…誇らしいですわ。', 'expression' => 'love_hint'],
                    ['text' => '正解ですわ♡ …もう…あなたのこと本当に好きかもですわ。', 'expression' => 'love_hint'],
                    ['text' => 'すごい、正解！わたくし…あなたのことが好きですわ♡', 'expression' => 'love_hint'],
                ],
                60 => [
                    ['text' => '正解♡ もう…嬉しすぎてどうしましょ。ずっと一緒に頑張りたいですわ！', 'expression' => 'love'],
                    ['text' => '正解です♡ あなたが正解するたびに…もっと好きになりますわ。', 'expression' => 'love'],
                    ['text' => '正解ですわ♡ …あなたのこと…本当に大好きですわ。', 'expression' => 'love'],
                ],
                70 => [
                    ['text' => '正解♡♡ あなたが正解するたびに…もっと好きになる感じですわ。', 'expression' => 'deep_love'],
                    ['text' => 'すごい、正解！…本当に誇らしいですわ。わたくしの大切な人ですもの。', 'expression' => 'deep_love'],
                    ['text' => '正解♡ …ねえ…本当に好きですわ。これはお勉強と関係ないお話ですわ。', 'expression' => 'deep_love'],
                ],
                80 => [
                    ['text' => '正解♡♡♡ えらい！もう…今すぐハグしたいですわ♡', 'expression' => 'max_love'],
                    ['text' => '正解！わたくしの大好きな人が正解するって…最高に嬉しいですわ♡', 'expression' => 'max_love'],
                    ['text' => '正解ですわ♡♡ あなたのこと…もっともっと好きになっちゃいました！', 'expression' => 'max_love'],
                ],
                100 => [
                    ['text' => '正解♡♡♡ わたくしね…あなたの全部が好きですわ。', 'expression' => 'max_love'],
                    ['text' => '正解♡ …あなたが正解するたびに…愛してるって思いますわ。', 'expression' => 'max_love'],
                    ['text' => 'やりました、正解ですわ♡♡♡ ずっと一緒にいてくれますですわね？', 'expression' => 'max_love'],
                ],
            ],
            'wrong' => [
                0 => [
                    ['text' => '残念ですわ。もっと頑張ってくださいですわね。', 'expression' => 'stern'],
                    ['text' => '不正解ですわ。解説をよくお読みになってくださいですわ。', 'expression' => 'stern'],
                    ['text' => '惜しいですわが…違いますわ。次頑張ってくださいですわ。', 'expression' => 'stern'],
                ],
                10 => [
                    ['text' => '惜しいですわ！でも違いますわね。解説をよくお読みになってください。', 'expression' => 'normal'],
                    ['text' => '不正解ですわ。でも大丈夫、一緒に確認しましょうですわ。', 'expression' => 'normal'],
                    ['text' => '違いますわね。でも惜しかったですわよ。次頑張ってくださいですわ。', 'expression' => 'normal'],
                ],
                20 => [
                    ['text' => 'あら、間違えちゃいましたですわ。解説読んで…また挑戦してくださいですわ。', 'expression' => 'smile'],
                    ['text' => '惜しかったですわ！でも大丈夫…次は絶対できますわ。', 'expression' => 'smile'],
                    ['text' => '不正解ですわ…。でも諦めないでくださいですわ。あなたなら大丈夫ですわ。', 'expression' => 'smile'],
                ],
                30 => [
                    ['text' => '違いますわね…。でも諦めないで。あなたなら覚えられますわ。', 'expression' => 'gentle'],
                    ['text' => 'ドンマイですわ♪ 失敗してもいいのですわ。それが勉強ですもの。', 'expression' => 'gentle'],
                    ['text' => '惜しかったですわね。解説一緒に読みましょうですわ。', 'expression' => 'gentle'],
                ],
                40 => [
                    ['text' => '間違えちゃいましたですわね。…でもね、こういう時こそ一緒に考えたいですわ。', 'expression' => 'blush'],
                    ['text' => '不正解ですわ…。でも…ね、一緒にもう一度確認しましょうですわ？', 'expression' => 'blush'],
                    ['text' => '惜しかったですわ…♡ こういう時…そばにいたいって思いますわ。', 'expression' => 'blush'],
                ],
                50 => [
                    ['text' => '違いますわ…。でも落ち込まないでくださいですわ。あなたのこと信じてますわ。', 'expression' => 'love_hint'],
                    ['text' => '惜しいですわ！…ねえ…一緒に解説読みませんですか？二人で考えましょうですわ。', 'expression' => 'love_hint'],
                    ['text' => '不正解ですわ…♡ でも大丈夫ですわ。わたくしがいますから。', 'expression' => 'love_hint'],
                ],
                60 => [
                    ['text' => '不正解ですわ…♡ でも大丈夫…。あなたが諦めたら…わたくし…悲しいですわ。', 'expression' => 'love'],
                    ['text' => '間違えちゃいましたですね…。でもわたくしはあなたを信じてますわ。ね…一緒に頑張りましょ。', 'expression' => 'love'],
                    ['text' => '違うですわ…♡ でも…そばにいますから…大丈夫ですわ。', 'expression' => 'love'],
                ],
                70 => [
                    ['text' => 'ドンマイですわ♡ 間違えても…あなたのこと好きですわ。一緒に覚えていきましょうですわ。', 'expression' => 'deep_love'],
                    ['text' => '不正解ですわ…でも大丈夫♡ ずっと一緒ですわ。何度でも挑戦してくださいですわ。', 'expression' => 'deep_love'],
                    ['text' => '違いますわ…♡♡ でも諦めないでくださいですわ。あなたなら絶対できますわ。', 'expression' => 'deep_love'],
                ],
                100 => [
                    ['text' => '違いますわ…でも♡♡♡ あなたのことを諦めさせたりしないですわ。ずっと一緒ですわ。', 'expression' => 'max_love'],
                    ['text' => 'ドンマイですわ♡♡ 間違えても…大好きですわ。また一緒に頑張りましょうですわ。', 'expression' => 'max_love'],
                    ['text' => '不正解ですわ…♡♡♡ でも大丈夫。あなたのそばにいますわ。', 'expression' => 'max_love'],
                ],
            ],
            default => [],
        };
    }

    // ===== 赤川 怜瑠（Rails） =====
    private static function railsDialogues(string $type): array
    {
        return match($type) {
            'greeting' => [
                0 => [
                    ['text' => 'はじめまして。赤川怜瑠です。…瑠璃花が何かお世話になってませんか？妹のことで何かあれば…', 'expression' => 'smile'],
                    ['text' => 'いらっしゃい。Railsを教えるけど…瑠璃花は？いや…いらっしゃいませ。', 'expression' => 'normal'],
                    ['text' => 'あ、来ました。…瑠璃花にいじめられてないといいんですが…心配で…いや…さあ始めましょ。', 'expression' => 'blush'],
                ],
                10 => [
                    ['text' => '10回ですか。…瑠璃花とはどんな勉強をしてるんですか？…羨ましい…っ！…ゴメン、大丈夫です。', 'expression' => 'blush'],
                    ['text' => '10回も来てくれたんですね。瑠璃花、喜ぶだろうな…いや、こちらこそ感謝です。', 'expression' => 'gentle'],
                    ['text' => 'また来てくれたんですね。…実は、毎日来るのを待ってたんです。…妹のためにです。', 'expression' => 'blush'],
                ],
                20 => [
                    ['text' => 'また来てくれたんですね。…瑠璃花のことよろしくお願いします。っていうか、嫉妬…いや…何でもありません。', 'expression' => 'blush'],
                    ['text' => '来てくれたんだ。瑠璃花のことをよく見てくれてるのかな…ありがとうございます。', 'expression' => 'gentle'],
                    ['text' => 'また来ていただいて…ありがとうございます。妹もきっと喜んでます。…僕も。', 'expression' => 'smile'],
                ],
                30 => [
                    ['text' => 'いらっしゃい。瑠璃花のこともよろしくお願いします。…最近、あなたのことばかり考えてます。…瑠璃花のことをです。', 'expression' => 'blush'],
                    ['text' => '来てくれた。…実は、あなたに会いたくてずっと待ってたんです。妹のためじゃなくて…自分のために。', 'expression' => 'blush'],
                    ['text' => 'また来てくれたんだ…♡ …正直に言うと、毎日のようにあなたのことばかり…妹のことばかり…混乱してます。', 'expression' => 'love_hint'],
                ],
                40 => [
                    ['text' => '来てくれたんだ…♡ 実は…君が好きなんです。瑠璃花のことじゃなくて、君が。', 'expression' => 'blush'],
                    ['text' => '来ていただいて嬉しいです♡ …正直に言うと…妹のことより…君のことが好きです。…最低ですね…ごめん。', 'expression' => 'blush'],
                    ['text' => '君が来てくれるのを待ってました♡ …これは…妹とは関係ない…純粋な気持ちです。', 'expression' => 'love_hint'],
                ],
                50 => [
                    ['text' => '君のこと…本当に大切なんです♡ 瑠璃花の面倒も…いや…そっちじゃなくて君が好きです！', 'expression' => 'love_hint'],
                    ['text' => '君といると…心が落ち着くんです♡ …妹とは別の…特別な気持ちです。', 'expression' => 'love_hint'],
                    ['text' => '来てくれてありがとうございます♡ …君のことが…本当に…大好きです。', 'expression' => 'love_hint'],
                ],
                60 => [
                    ['text' => 'ずっと傍にいてくれませんか…？♡ 君のことが大好きです。', 'expression' => 'love'],
                    ['text' => '君のことが大好きです…♡ 妹のシスコンじゃなくて…君への想い…本気です。', 'expression' => 'love'],
                    ['text' => '君といると…完璧になれる気がします♡ …君のそばが…一番いい場所です。', 'expression' => 'love'],
                ],
                70 => [
                    ['text' => 'いつも…ありがとうございます♡ 君といると…心が穏やかになるんです。', 'expression' => 'deep_love'],
                    ['text' => 'ずっと一緒にいてください♡ …君がいてくれるから…僕は…生きていけるんです。', 'expression' => 'deep_love'],
                    ['text' => 'あなたのそばが一番安心します♡ …妹よりも…君を選んでしまいそうで怖いです。', 'expression' => 'deep_love'],
                ],
                80 => [
                    ['text' => 'お疲れ様です♡♡ 大好きな君と毎日会えるって…本当に幸せです。', 'expression' => 'max_love'],
                    ['text' => '来てくれてありがとう♡♡ …君のことが…世界で一番…好きです。', 'expression' => 'max_love'],
                    ['text' => '君のそばにいられるだけで♡♡ 僕は…全てを手に入れた気がします。', 'expression' => 'max_love'],
                ],
                100 => [
                    ['text' => '♡♡♡ ずっと一緒にいてください。…君がいてくれるから…僕は完全でいられるんです。', 'expression' => 'max_love'],
                    ['text' => '♡♡♡ …君が全てです。ずっと…ずっと…そばにいてください。', 'expression' => 'max_love'],
                    ['text' => '♡♡♡ 君を愛してます。…妹よりも…世界中の誰よりも…君が好きです。', 'expression' => 'max_love'],
                ],
            ],
            'correct' => [
                0 => [
                    ['text' => 'はい、正解です。いいですね。', 'expression' => 'smile'],
                    ['text' => '正解ですね。いい調子です。', 'expression' => 'normal'],
                    ['text' => 'その通り。正解です。', 'expression' => 'smile'],
                ],
                10 => [
                    ['text' => '正解！…また正解されました。嬉しいです。妹も喜びますよ…羨ましい。', 'expression' => 'blush'],
                    ['text' => '正解ですね。だんだん上達してきました。いいです…羨ましい…いや…。', 'expression' => 'blush'],
                    ['text' => 'はい、正解！…あなたの成長…本当に素晴らしいです。', 'expression' => 'smile'],
                ],
                20 => [
                    ['text' => '正解〜！よくできました♪ …あなたの頑張り…好きです。', 'expression' => 'smile'],
                    ['text' => '正解です！すごい…すごく素敵です。', 'expression' => 'smile'],
                    ['text' => 'やっぱり正解ですね。素晴らしい。その調子で…♪', 'expression' => 'smile'],
                ],
                30 => [
                    ['text' => '正解っ♪ …あなたが正解するたびに…嬉しくなります。妹以上に…。', 'expression' => 'happy'],
                    ['text' => '正解！…本当に嬉しいです。あなたの成長…全部見てましたよ。', 'expression' => 'happy'],
                    ['text' => 'やりました！正解です♪ 最高です…あなたは…最高です。', 'expression' => 'happy'],
                ],
                40 => [
                    ['text' => '正解♡ …あなたが正解するの…本当に好きです。この気持ち…何なんでしょう。', 'expression' => 'blush'],
                    ['text' => '正解です♡ …あなたが正解するたびに…心がときめきます。', 'expression' => 'blush'],
                    ['text' => '正解♡ …もう…あなたのこと…好きすぎて…困ってます…。', 'expression' => 'blush'],
                ],
                50 => [
                    ['text' => '正解♡ …あなたが正解するの…本当に誇らしいです。君のために…。', 'expression' => 'love_hint'],
                    ['text' => '正解です♡ …一緒に勉強してきてよかったって…思います。', 'expression' => 'love_hint'],
                    ['text' => 'すごい、正解！♡ …もう…あなたのこと…本当に…。', 'expression' => 'love_hint'],
                ],
                60 => [
                    ['text' => '正解♡ …もう…嬉しすぎてどうしよう。ずっと一緒に頑張ってきてよかった！', 'expression' => 'love'],
                    ['text' => '正解です♡ …あなたが正解するたびに…もっと好きになります。', 'expression' => 'love'],
                    ['text' => '正解♡ …あなことが…大好きです。これは…本気です。', 'expression' => 'love'],
                ],
                70 => [
                    ['text' => '正解♡♡ …あなたが正解するたびに…もっと好きになるんです。', 'expression' => 'deep_love'],
                    ['text' => 'すごい、正解です！…本当に…誇らしいです。君のことが…全てです。', 'expression' => 'deep_love'],
                    ['text' => '正解♡ …ねえ…本当に好きです。この気持ち…もう隠せません。', 'expression' => 'deep_love'],
                ],
                80 => [
                    ['text' => '正解♡♡♡ えらい！…もう…君を抱きしめたいくらいです。', 'expression' => 'max_love'],
                    ['text' => '正解です！…大好きな君が正解するって…最高に嬉しいです。', 'expression' => 'max_love'],
                    ['text' => '正解♡♡ 君…本当に素敵です。もっともっと好きになっちゃいました。', 'expression' => 'max_love'],
                ],
                100 => [
                    ['text' => '正解♡♡♡ …君の全部が好きです。大好きです。', 'expression' => 'max_love'],
                    ['text' => '正解♡ …君が正解するたびに…愛してるって思います。', 'expression' => 'max_love'],
                    ['text' => 'やりました、正解♡♡♡ ずっと一緒にいてください…。', 'expression' => 'max_love'],
                ],
            ],
            'wrong' => [
                0 => [
                    ['text' => 'そうですね…残念です。もっと頑張ってください。', 'expression' => 'stern'],
                    ['text' => '不正解ですね。解説をよく読んでください。', 'expression' => 'stern'],
                    ['text' => '惜しいですが…違いますね。次、頑張ってください。', 'expression' => 'stern'],
                ],
                10 => [
                    ['text' => '惜しい！でも違いますね。解説をよく読んでください。', 'expression' => 'normal'],
                    ['text' => '不正解です。でも大丈夫…一緒に確認しましょう。', 'expression' => 'normal'],
                    ['text' => '違いますね。でも惜しかったです。次は頑張れます。', 'expression' => 'normal'],
                ],
                20 => [
                    ['text' => 'あぁ、間違えちゃった。でも…解説読んでまた挑戦してください。', 'expression' => 'smile'],
                    ['text' => '惜しかった！でも大丈夫…次は絶対できますよ。', 'expression' => 'smile'],
                    ['text' => '不正解ですね…。でも諦めないで。あなたなら大丈夫です。', 'expression' => 'smile'],
                ],
                30 => [
                    ['text' => '違いますね…。でも諦めないで。あなたなら覚えられます。', 'expression' => 'gentle'],
                    ['text' => 'ドンマイですね♪ 失敗してもいいんです…それが勉強ですから。', 'expression' => 'gentle'],
                    ['text' => '惜しかったですね。解説一緒に読みましょう。', 'expression' => 'gentle'],
                ],
                40 => [
                    ['text' => '間違えちゃった…。でもね…こういう時…一緒に考えたいんです。', 'expression' => 'blush'],
                    ['text' => '不正解ですね…。でも…一緒にもう一度確認しませんか？', 'expression' => 'blush'],
                    ['text' => '惜しかった…♡ …こういう時…そばにいたいって思います。', 'expression' => 'blush'],
                ],
                50 => [
                    ['text' => '違いますね…。でも落ち込まないでください。君のこと信じてますから。', 'expression' => 'love_hint'],
                    ['text' => '惜しい！…ねえ…一緒に解説読みませんか？二人で考えましょう。', 'expression' => 'love_hint'],
                    ['text' => '不正解ですね…♡ でも大丈夫です。僕がいますから。', 'expression' => 'love_hint'],
                ],
                60 => [
                    ['text' => '不正解…♡ でも大丈夫。君が諦めたら…僕も…悲しいです。', 'expression' => 'love'],
                    ['text' => '間違えちゃった…。でも君を信じてます。ね…一緒に頑張りましょう。', 'expression' => 'love'],
                    ['text' => '違いますね…♡ でも…そばにいますから…大丈夫です。', 'expression' => 'love'],
                ],
                70 => [
                    ['text' => 'ドンマイです♡ …間違えても…君のこと好きですよ。一緒に覚えていこう。', 'expression' => 'deep_love'],
                    ['text' => '不正解ですね…でも大丈夫♡ ずっと一緒です。何度でも挑戦してください。', 'expression' => 'deep_love'],
                    ['text' => '違いますね…♡♡ でも諦めないでください。君なら絶対できます。', 'expression' => 'deep_love'],
                ],
                100 => [
                    ['text' => '違いますね…でも♡♡♡ 君のことを諦めさせたりしません。ずっと一緒です。', 'expression' => 'max_love'],
                    ['text' => 'ドンマイです♡♡ …間違えても…大好きですよ。また一緒に頑張りましょう。', 'expression' => 'max_love'],
                    ['text' => '不正解ですね…♡♡♡ でも大丈夫。君のそばにいます。', 'expression' => 'max_love'],
                ],
            ],
            default => [],
        };
    }
}
