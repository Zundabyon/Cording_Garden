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
            'php'     => static::phpDialogues($type),
            'laravel' => static::laravelDialogues($type),
            'error'   => static::errorDialogues($type),
            default   => static::defaultDialogues($type),
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
}
