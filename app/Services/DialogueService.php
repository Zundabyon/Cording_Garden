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

        // 完全一致するキーがあればそれを使う
        if (isset($data[$affection])) {
            return $data[$affection];
        }

        // 最も近い小さいキーを探す
        $keys = array_keys($data);
        $floor = 0;
        foreach ($keys as $k) {
            if ($k <= $affection) {
                $floor = $k;
            }
        }
        return $data[$floor] ?? ['text' => 'よろしく。', 'expression' => 'normal'];
    }

    private static function getData(string $slug, string $type): array
    {
        return match($slug) {
            'php'        => static::phpDialogues($type),
            'laravel'    => static::laravelDialogues($type),
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
        return $data[$floor] ?? ['text' => 'お疲れ様でした。', 'expression' => 'normal'];
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
                0  => ['text' => '…あなたが新入りね。PHPを教えてあげるけど、覚悟はいい？', 'expression' => 'cool'],
                1  => ['text' => 'また来たの。まあ、根気だけはあるのね。', 'expression' => 'cool'],
                2  => ['text' => '…何の用？勉強するなら付き合ってあげるわ。', 'expression' => 'cool'],
                3  => ['text' => '来たわね。今日も問題出してあげる。', 'expression' => 'normal'],
                4  => ['text' => '座って。始めましょ。', 'expression' => 'normal'],
                5  => ['text' => '今日は何を覚えたいの？まあ、私が決めるけど。', 'expression' => 'normal'],
                6  => ['text' => '少しはマシになってきたかしら。では問題ね。', 'expression' => 'normal'],
                7  => ['text' => 'また来てくれたのね。…悪くない姿勢じゃない。', 'expression' => 'normal'],
                8  => ['text' => 'やる気があるのは認めてあげる。さあ始めましょ。', 'expression' => 'normal'],
                9  => ['text' => '継続は力なり、ってね。今日も頑張りなさい。', 'expression' => 'normal'],
                10 => ['text' => '10回目ね。…少し見直したわ。今日も問題よ。', 'expression' => 'slight_smile'],
                11 => ['text' => '来たわ。…最近ちゃんと来るのね、あなた。', 'expression' => 'slight_smile'],
                12 => ['text' => '時間通りに来るなんて、意外とマメなのね。', 'expression' => 'slight_smile'],
                13 => ['text' => 'また来てくれたの。…うん、悪くないわ。', 'expression' => 'slight_smile'],
                14 => ['text' => '今日もやる気ある顔してるじゃない。期待してあげる。', 'expression' => 'slight_smile'],
                15 => ['text' => '最近サボらずに来るわね。…ちょっと嬉しいかも。', 'expression' => 'slight_smile'],
                16 => ['text' => 'あら、今日も来たの。…来るとわかってたけどね。', 'expression' => 'slight_smile'],
                17 => ['text' => '一緒に勉強するのも、悪くないわね。さあ問題よ。', 'expression' => 'slight_smile'],
                18 => ['text' => '顔覚えてきたわ。…そろそろ名前も教えなさいよ。', 'expression' => 'slight_smile'],
                19 => ['text' => 'あなたのこと、少しだけわかってきた気がする。', 'expression' => 'slight_smile'],
                20 => ['text' => '明空くん、今日も来てくれたの。一緒に頑張りましょ。', 'expression' => 'smile'],
                21 => ['text' => '来てくれると思ってたわ。じゃあ始めましょうか。', 'expression' => 'smile'],
                22 => ['text' => '最近あなたが来るのが当たり前になってきた気がする。', 'expression' => 'smile'],
                23 => ['text' => 'また会えたわね。…なんだかんだ楽しいのよ、一緒に勉強するの。', 'expression' => 'smile'],
                24 => ['text' => 'ふふ、今日はどんな問題にする？って、私が決めるんだけど。', 'expression' => 'smile'],
                25 => ['text' => '明空くん♪ ちょうど待ってたとこ。さあ始めよ！', 'expression' => 'smile'],
                26 => ['text' => '今日は少し難しい問題にしようかな。あなたなら大丈夫でしょ。', 'expression' => 'smile'],
                27 => ['text' => 'だんだん実力ついてきたわね。嬉しいわ、私。', 'expression' => 'smile'],
                28 => ['text' => '来てくれると、なんかやる気が出るのよね…不思議ね。', 'expression' => 'smile'],
                29 => ['text' => 'あなたと一緒に勉強してると、時間が経つの早い気がする。', 'expression' => 'smile'],
                30 => ['text' => '今日も来てくれた。…ねえ、実は来るの楽しみにしてたんだから。', 'expression' => 'happy'],
                31 => ['text' => 'あら明空くん。今日は少し早いじゃない。待ってたわよ。', 'expression' => 'happy'],
                32 => ['text' => 'また会えた♪ 今日もいっぱい教えてあげるからね。', 'expression' => 'happy'],
                33 => ['text' => '一緒に問題解くの、気がついたら好きになってたわ。', 'expression' => 'happy'],
                34 => ['text' => 'ふふ、今日も頑張ろっか！私、あなたを応援してるわよ。', 'expression' => 'happy'],
                35 => ['text' => 'こんにちは♪ 今日もね、ちょっと楽しみだったの。', 'expression' => 'happy'],
                36 => ['text' => '明空くんが来ると、なんだか部屋が明るくなる感じがするのよね。', 'expression' => 'happy'],
                37 => ['text' => 'また来てくれた！…って、顔がにやけちゃうじゃない。恥ずかし。', 'expression' => 'blush'],
                38 => ['text' => 'あなたが来るのを待ってたって言ったら、引く？', 'expression' => 'blush'],
                39 => ['text' => '…ねえ、最近一緒に勉強するの、すごく楽しいって思ってる。', 'expression' => 'blush'],
                40 => ['text' => '来てくれた…♪ 最近、あなたのこと気になってるかも、私。', 'expression' => 'blush'],
                41 => ['text' => 'あらあら、今日も来たの。…もう、顔見るとドキドキするじゃない。', 'expression' => 'blush'],
                42 => ['text' => '明空くん。今日ね、あなたに会えると思ったら朝から元気だったの。', 'expression' => 'blush'],
                43 => ['text' => 'また会えた。…これ、毎回ドキドキしてるの、私だけ？', 'expression' => 'blush'],
                44 => ['text' => '…こんなに来てくれると、好きになっちゃうわよ？本当のことを言うと。', 'expression' => 'blush'],
                45 => ['text' => '今日もね、あなたのことを考えてたの。…変かしら。', 'expression' => 'blush'],
                46 => ['text' => '顔見るたびに、なんだかあったかい気持ちになる。', 'expression' => 'blush'],
                47 => ['text' => 'ねえ明空くん。私、最近ずっと気になってること があるんだけど。…問題の話よ！', 'expression' => 'blush'],
                48 => ['text' => 'また来てくれた。…ありがとう、って言いたくなる。', 'expression' => 'blush'],
                49 => ['text' => '最近あなたのことばかり考えてる気がして、自分でびっくりしてる。', 'expression' => 'blush'],
                50 => ['text' => '明空くん…♡ 来てくれると思ってた。今日も一緒に頑張ろ。', 'expression' => 'love_hint'],
                51 => ['text' => 'あなたがいると、なんでこんなに頑張れるんだろう。不思議。', 'expression' => 'love_hint'],
                52 => ['text' => 'また会えた…♪ もう、待ってたって顔に出てた？', 'expression' => 'love_hint'],
                53 => ['text' => '一緒にいる時間が、私の中で特別になってきてる気がする。', 'expression' => 'love_hint'],
                54 => ['text' => '今日もね、問題の前にあなたの顔が見れてよかった。', 'expression' => 'love_hint'],
                55 => ['text' => 'ねえ…もしかして私、あなたのことが好きなのかもしれない。', 'expression' => 'love_hint'],
                56 => ['text' => '来てくれた。…もう、ちょっと好きすぎてどうしようかと思ってる。', 'expression' => 'love_hint'],
                57 => ['text' => '明空くん、あのね…ごめん、なんでもない。問題始めましょ。', 'expression' => 'love_hint'],
                58 => ['text' => 'あなたに会いたくて、早く来てしまったわ。…恥ずかしいけど。', 'expression' => 'love_hint'],
                59 => ['text' => 'ふふ、今日もあなたと勉強できる。それだけで嬉しいの、私。', 'expression' => 'love_hint'],
                60 => ['text' => '明空くん…♡ もう、毎日会いたいって思ってるの。', 'expression' => 'love'],
                61 => ['text' => 'また来てくれた。今日は、前より早く会いたかったかも。', 'expression' => 'love'],
                62 => ['text' => 'ねえ…あなたのこと、好きよ。勉強だけじゃなくて、その話はまた今度ね。', 'expression' => 'love'],
                63 => ['text' => '来てくれると、心がぽかぽかするの。あなたがそうさせてるの。', 'expression' => 'love'],
                64 => ['text' => '今日もあなたの顔見れた。…それが一番嬉しいかも。', 'expression' => 'love'],
                65 => ['text' => '明空くん♡ 会いたかったわ。ちょっとだけ、正直に言うと。', 'expression' => 'love'],
                66 => ['text' => 'またね、今日も来てくれた。もう、ありがとうって言いたい気持ち。', 'expression' => 'love'],
                67 => ['text' => 'あなたのことを考えてると、勉強も捗るの。不思議でしょ？', 'expression' => 'love'],
                68 => ['text' => '…来てくれてよかった。ひとりだと少し、寂しかったから。', 'expression' => 'love'],
                69 => ['text' => 'ねえ、私のこと少しは気になってる…？なんて、聞けないわ。', 'expression' => 'love'],
                70 => ['text' => '明空くん♡ あなたに会えると、今日一日が特別になる気がする。', 'expression' => 'deep_love'],
                71 => ['text' => 'また会えた…。毎回こんなに嬉しいって、もう隠せないわ。', 'expression' => 'deep_love'],
                72 => ['text' => 'あなたのそばにいると、なんでも頑張れそうな気がするの。', 'expression' => 'deep_love'],
                73 => ['text' => '今日もね、あなたに会いたかった。もう毎日思ってるわ。', 'expression' => 'deep_love'],
                74 => ['text' => 'ねえ明空くん…私、あなたのことが大切なの。それだけは伝えたくて。', 'expression' => 'deep_love'],
                75 => ['text' => '…来てくれた。ありがとう。それだけで、今日は頑張れる。', 'expression' => 'deep_love'],
                76 => ['text' => '一緒にいると、時間があっという間なの。もっといたいって思う。', 'expression' => 'deep_love'],
                77 => ['text' => 'あなたのことを想うと、胸がいっぱいになるの。困った。', 'expression' => 'deep_love'],
                78 => ['text' => '明空くん♡ 会いたかったって言ったら、変かしら。', 'expression' => 'deep_love'],
                79 => ['text' => '…なんだか、あなたのそばが一番居心地よくなってきた。', 'expression' => 'deep_love'],
                80 => ['text' => '大好きな人に会えた♡ 今日も一緒に頑張りましょ、明空くん。', 'expression' => 'max_love'],
                81 => ['text' => 'また来てくれた…♡ 毎回こんなに嬉しくていいの？', 'expression' => 'max_love'],
                82 => ['text' => 'あなたがいてくれるだけで、全部うまくいく気がするわ。', 'expression' => 'max_love'],
                83 => ['text' => '明空くん、大好き♡ って、問題前に言うのはずるいかしら。', 'expression' => 'max_love'],
                84 => ['text' => 'ねえ…来てくれてありがとう。本当に、いつも。', 'expression' => 'max_love'],
                85 => ['text' => 'また会えた♡ もう毎日あなたに会いたいって思ってるわ。', 'expression' => 'max_love'],
                86 => ['text' => 'あなたが来るたびに、もっと好きになってる気がする。', 'expression' => 'max_love'],
                87 => ['text' => '…私ね、あなたのこと、ずっと大切にしたいって思ってる。', 'expression' => 'max_love'],
                88 => ['text' => '来てくれた♡ もう、あなたのことが一番大切よ。', 'expression' => 'max_love'],
                89 => ['text' => '明空くん…♡ ただいって言いたいくらい、ここが特別になった。', 'expression' => 'max_love'],
                90 => ['text' => '♡ 大好き、明空くん。毎回会うたびにそう思う。', 'expression' => 'max_love'],
                91 => ['text' => 'あなたがいてくれると、なんでもできる気がするわ。', 'expression' => 'max_love'],
                92 => ['text' => 'ねえ…ずっと一緒にいてほしいって、思っていいかしら。', 'expression' => 'max_love'],
                93 => ['text' => '来てくれた♡ あなたを待ってる時間も、幸せよ。', 'expression' => 'max_love'],
                94 => ['text' => '明空くん大好き♡ いつも来てくれてありがとう。', 'expression' => 'max_love'],
                95 => ['text' => '…あなたのそばにいると、時間が止まればいいって思う。', 'expression' => 'max_love'],
                96 => ['text' => '毎日あなたに会えること、当たり前にしてしまってるけど…すごく幸せよ。', 'expression' => 'max_love'],
                97 => ['text' => '明空くん♡ ねえ、今日も一緒にいてくれる？', 'expression' => 'max_love'],
                98 => ['text' => '…あなたのことが大好きで、どうしようもないの。', 'expression' => 'max_love'],
                99 => ['text' => '来てくれた♡ もう…言葉にならないくらい嬉しい。', 'expression' => 'max_love'],
                100 => ['text' => '♡♡♡ 明空くん、大好き。ずっとそばにいてね。', 'expression' => 'max_love'],
            ],
            'correct' => [
                0  => ['text' => '…正解。まあ、当然ね。', 'expression' => 'cool'],
                2  => ['text' => '正解。悪くないじゃない。', 'expression' => 'cool'],
                5  => ['text' => '正解よ。ちゃんと覚えてたのね。', 'expression' => 'normal'],
                8  => ['text' => '正解。…少しずつ力ついてきてるわ。', 'expression' => 'normal'],
                10 => ['text' => '正解！まあ、この程度はね。でも褒めてあげる。', 'expression' => 'slight_smile'],
                14 => ['text' => '正解よ！やるじゃない。', 'expression' => 'slight_smile'],
                18 => ['text' => '正解！最近安定してきたわ。', 'expression' => 'slight_smile'],
                20 => ['text' => '正解〜！よくできました♪', 'expression' => 'smile'],
                24 => ['text' => '正解！えらいえらい♪ 頭いいじゃない。', 'expression' => 'smile'],
                28 => ['text' => 'やった、正解！一緒に勉強した甲斐あるわ。', 'expression' => 'smile'],
                30 => ['text' => '正解っ♪ あなたが正解するたびに、私まで嬉しくなるの。', 'expression' => 'happy'],
                35 => ['text' => '正解！すごいすごい！本当に伸びてるわ〜。', 'expression' => 'happy'],
                40 => ['text' => '正解♡ やっぱりあなたは飲み込みが早い。好きよ、そういうとこ。', 'expression' => 'blush'],
                45 => ['text' => '正解！…ねえ、ちょっと抱きしめたくなった。…ダメよね。', 'expression' => 'blush'],
                50 => ['text' => '正解♡ あなたが正解するの、なんか誇らしいのよね。', 'expression' => 'love_hint'],
                55 => ['text' => '正解っ！…またひとつ賢くなったね。ずっと一緒に頑張ろ。', 'expression' => 'love_hint'],
                60 => ['text' => '正解♡ もう、嬉しすぎてどうしよう。一緒に頑張ってきてよかった！', 'expression' => 'love'],
                65 => ['text' => '正解！大好き♡ …問題の話よ、これは。うそ、そうじゃないかも。', 'expression' => 'love'],
                70 => ['text' => '正解♡♡ あなたが正解するたびに、もっと好きになる気がする。', 'expression' => 'deep_love'],
                75 => ['text' => 'すごい、正解！…本当に誇らしいわ。私の大切な人だもの。', 'expression' => 'deep_love'],
                80 => ['text' => '正解♡♡♡ えらい！もう、今すぐハグしたい！', 'expression' => 'max_love'],
                85 => ['text' => '正解！大好きな人が正解するって最高に嬉しいわ♡', 'expression' => 'max_love'],
                90 => ['text' => '正解っ♡ あなたのこと、もっともっと好きになった！', 'expression' => 'max_love'],
                95 => ['text' => '正解♡♡ …ずっとこんな時間が続けばいいのに。', 'expression' => 'max_love'],
                100 => ['text' => '正解♡♡♡ 大好き、明空くん。あなたの全部が好き！', 'expression' => 'max_love'],
            ],
            'wrong' => [
                0  => ['text' => '不正解。もっとちゃんと勉強しなさい。', 'expression' => 'stern'],
                3  => ['text' => '違うわ。解説をよく読んで。', 'expression' => 'stern'],
                6  => ['text' => '惜しいけど不正解。次は頑張って。', 'expression' => 'normal'],
                10 => ['text' => '惜しい！でも違うわ。解説をよく読んでね。', 'expression' => 'normal'],
                15 => ['text' => '不正解ね。大丈夫、一緒に確認しましょ。', 'expression' => 'slight_smile'],
                20 => ['text' => 'あら、間違えちゃった。解説読んで、また挑戦してみて。', 'expression' => 'smile'],
                25 => ['text' => '惜しかった！でも大丈夫、次は絶対できるわ。', 'expression' => 'smile'],
                30 => ['text' => '違うわ…。でも諦めないで。あなたならすぐ覚えられる。', 'expression' => 'gentle'],
                35 => ['text' => 'ドンマイ♪ 失敗してもいいの、それが勉強だから。', 'expression' => 'gentle'],
                40 => ['text' => '間違えたわね。…でもね、こういう時こそ一緒に考えたいの。', 'expression' => 'blush'],
                45 => ['text' => '不正解…。うーん、一緒にもう一度確認しよ？', 'expression' => 'blush'],
                50 => ['text' => '違うわ…。でも落ち込まないで。あなたのこと信じてるから。', 'expression' => 'love_hint'],
                55 => ['text' => '惜しい！…ねえ、一緒に解説読もうか。二人で考えよ。', 'expression' => 'love_hint'],
                60 => ['text' => '不正解…♡ でも大丈夫よ。あなたが諦めたら、私が悲しい。', 'expression' => 'love'],
                65 => ['text' => '間違えたね…でも私はあなたを信じてるわ。次は一緒に頑張ろ。', 'expression' => 'love'],
                70 => ['text' => 'ドンマイ♡ 間違えても、あなたのこと好きよ。一緒に覚えよ。', 'expression' => 'deep_love'],
                80 => ['text' => '不正解…でも大丈夫♡ 一緒にいるから。何度でも挑戦しよ。', 'expression' => 'max_love'],
                90 => ['text' => 'ドンマイ♡♡ 間違えても大好きよ。また一緒に頑張ろ。', 'expression' => 'max_love'],
                100 => ['text' => '違うわ…でも♡ あなたのことを諦めさせたりしないわ。一緒にいるから。', 'expression' => 'max_love'],
            ],
            default => [],
        };
    }

    // ===== 神宮寺 らら美（Laravel） =====
    private static function laravelDialogues(string $type): array
    {
        return match($type) {
            'greeting' => [
                0  => ['text' => 'あら、初めまして。私のことはらら美と呼んで。丁寧に教えてあげるわ。', 'expression' => 'elegant'],
                1  => ['text' => 'また来たのね。Laravelの美しさ、少しずつ分かってきた？', 'expression' => 'elegant'],
                2  => ['text' => '来てくれたのね。優雅に、そして素早く。それが私のモットーよ。', 'expression' => 'elegant'],
                3  => ['text' => 'ふふ、またあなたね。今日は何を教えましょうか。', 'expression' => 'elegant'],
                4  => ['text' => '座って。今日も一緒にLaravelを学びましょう。', 'expression' => 'elegant'],
                5  => ['text' => '今日も来てくれたわ。見どころがあるじゃない。', 'expression' => 'elegant'],
                6  => ['text' => 'あら、またお会いできたわね。勉強しましょ。', 'expression' => 'normal'],
                7  => ['text' => 'いらっしゃい。今日もLaravelの世界に誘ってあげる。', 'expression' => 'normal'],
                8  => ['text' => '来てくれて嬉しいわ。さっそく始めましょうか。', 'expression' => 'normal'],
                9  => ['text' => '毎回来てくれるのね。誠実なところが好きよ、そういうの。', 'expression' => 'slight_smile'],
                10 => ['text' => '10回ね。…なかなか筋がいいじゃない、あなた。', 'expression' => 'slight_smile'],
                11 => ['text' => '来てくれたわ。今日もLaravelの奥深さ、一緒に感じましょ。', 'expression' => 'slight_smile'],
                12 => ['text' => 'ふふ、また会えたわ。少し楽しみにしていたのよ。', 'expression' => 'slight_smile'],
                13 => ['text' => 'いらっしゃい。最近あなたの成長が見えてきたわ。', 'expression' => 'slight_smile'],
                14 => ['text' => '来てくれると思ってたわ。期待してるのよ、あなたに。', 'expression' => 'slight_smile'],
                15 => ['text' => 'ふふ、今日も来てくれたのね。うれしいわ。', 'expression' => 'slight_smile'],
                16 => ['text' => 'あなたのこと、少しずつわかってきた気がするわ。', 'expression' => 'slight_smile'],
                17 => ['text' => 'また会えてよかった。今日も優雅にいきましょう。', 'expression' => 'slight_smile'],
                18 => ['text' => '来るたびに雰囲気が変わるのよね、あなた。良い意味で。', 'expression' => 'slight_smile'],
                19 => ['text' => '継続の美しさね。今日も一緒に学びましょう。', 'expression' => 'slight_smile'],
                20 => ['text' => '明空くん、来てくれたわ。一緒に優雅なコードを書きましょう。', 'expression' => 'smile'],
                25 => ['text' => 'ふふ、今日も来てくれたのね。実はね、楽しみにしてたの。', 'expression' => 'smile'],
                30 => ['text' => 'また会えた♪ …あなたと話すの、好きかもしれないわ。', 'expression' => 'happy'],
                35 => ['text' => '来てくれて嬉しい♪ 今日は特別難しい問題にしようかしら。', 'expression' => 'happy'],
                40 => ['text' => '明空くん♡ 待ってたわよ。…ちょっとだけね。', 'expression' => 'blush'],
                45 => ['text' => 'また来てくれた♡ …もう、嬉しすぎてどう言えばいいか。', 'expression' => 'blush'],
                50 => ['text' => '…来てくれた。実はずっとここで待ってたの。変かしら？', 'expression' => 'love_hint'],
                55 => ['text' => '明空くん、あのね…会いたかったわ。問題の話だけじゃなくてね。', 'expression' => 'love_hint'],
                60 => ['text' => '来てくれた♡ …あなたのこと、大好きよ。知ってた？', 'expression' => 'love'],
                65 => ['text' => 'また会えた♡ …一緒にいると、なんだか世界が輝いて見えるの。', 'expression' => 'love'],
                70 => ['text' => '明空くん♡ あなたといる時間が、私の一番大切な時間なの。', 'expression' => 'deep_love'],
                80 => ['text' => '来てくれた♡♡ …もう、あなたがいないと寂しくなっちゃったわ。', 'expression' => 'max_love'],
                90 => ['text' => '♡♡ 大好きよ、明空くん。来てくれてありがとう。', 'expression' => 'max_love'],
                100 => ['text' => '♡♡♡ らら美ね、明空くんのことが世界で一番好きよ。', 'expression' => 'max_love'],
            ],
            'correct' => [
                0  => ['text' => '正解ですわ。さすがに基礎はできているのね。', 'expression' => 'elegant'],
                5  => ['text' => 'ふふ、正解。少しずつ上達してるわ。', 'expression' => 'slight_smile'],
                10 => ['text' => '正解！素晴らしいわ。この調子でどんどん行きましょう。', 'expression' => 'smile'],
                20 => ['text' => '正解♪ あなた、本当に伸びてるわ。教えがいがある。', 'expression' => 'happy'],
                30 => ['text' => '正解っ！…明空くん、最近すごく輝いてるわよ。', 'expression' => 'happy'],
                40 => ['text' => '正解♡ あなたが正解するたびに、私まで嬉しくなってしまうの。', 'expression' => 'blush'],
                50 => ['text' => '正解♡ …おかしいかしら、あなたの成長がこんなに嬉しいなんて。', 'expression' => 'love_hint'],
                60 => ['text' => '正解♡♡ 大好きな人が正解する瞬間って、最高に嬉しいわ。', 'expression' => 'love'],
                70 => ['text' => '正解♡♡ もう、抱きしめたいくらい嬉しいわ！', 'expression' => 'deep_love'],
                80 => ['text' => '正解♡♡♡ 素晴らしい！本当に、あなたのことが誇らしい！', 'expression' => 'max_love'],
                100 => ['text' => '正解♡♡♡♡ 大好き！ずっと一緒に頑張ってきてよかった！', 'expression' => 'max_love'],
            ],
            'wrong' => [
                0  => ['text' => '不正解ですわ。Laravelをなめてはいけないわ。', 'expression' => 'stern'],
                5  => ['text' => '惜しいけど不正解。解説をよく読んで覚えておいてね。', 'expression' => 'normal'],
                10 => ['text' => '残念！でも間違えるのも勉強よ。一緒に確認しましょ。', 'expression' => 'slight_smile'],
                20 => ['text' => '惜しかった！次は一緒に考えましょ。', 'expression' => 'smile'],
                30 => ['text' => 'ドンマイ♪ 失敗しても前に進めるのが大切よ。', 'expression' => 'gentle'],
                40 => ['text' => '間違えたの…うふふ、そういうところも可愛いわ。一緒に確認しましょ。', 'expression' => 'blush'],
                50 => ['text' => '不正解…。でも諦めないで。あなたが諦めたら、私が悲しい。', 'expression' => 'love_hint'],
                60 => ['text' => 'ドンマイ♡ …間違えても、あなたのことが好きよ。一緒に覚えましょ。', 'expression' => 'love'],
                80 => ['text' => '不正解…♡♡ でも大丈夫。私がいるから。一緒に乗り越えましょ。', 'expression' => 'max_love'],
                100 => ['text' => 'ドンマイ♡♡♡ …何度間違えても、あなたのそばにいるわ。', 'expression' => 'max_love'],
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
}
