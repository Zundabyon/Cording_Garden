<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $fillable = [
        'slug', 'name', 'name_kana', 'gender', 'personality', 'subject',
        'description', 'is_unlocked', 'sort_order',
    ];

    protected $casts = [
        'is_unlocked' => 'boolean',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function affections()
    {
        return $this->hasMany(Affection::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function getColorClass(): string
    {
        return match($this->subject) {
            'php' => 'from-purple-500 to-indigo-600',
            'laravel' => 'from-red-500 to-pink-600',
            'html' => 'from-orange-500 to-red-500',
            'css' => 'from-blue-400 to-cyan-500',
            'js' => 'from-yellow-400 to-amber-500',
            'typescript' => 'from-blue-600 to-blue-800',
            'vue' => 'from-green-400 to-emerald-500',
            'error' => 'from-gray-700 to-gray-900',
            default => 'from-gray-400 to-gray-600',
        };
    }

    public function getEmojiIcon(): string
    {
        return match($this->subject) {
            'php' => '🐘',
            'laravel' => '🔴',
            'html' => '📄',
            'css' => '🎨',
            'js' => '⚡',
            'typescript' => '💙',
            'vue' => '💚',
            'error' => '⛔',
            default => '💻',
        };
    }

    // 好感度レベル(0-4)に応じた開始セリフ
    public function getGreetingDialogue(int $affectionLevel): array
    {
        $dialogues = [
            'php' => [
                0 => ['text' => '...あなたが新入りね。PHPを教えてあげるけど、覚悟はいい？', 'expression' => 'normal'],
                1 => ['text' => 'また来てくれたの。じゃあ今日も一緒に頑張りましょうか。', 'expression' => 'smile'],
                2 => ['text' => '風呂蔵くん♪ 待ってたわ。今日はどんな問題にする？', 'expression' => 'happy'],
                3 => ['text' => '来てくれると思ってた。一緒に勉強するのが最近楽しくなってきたかも…', 'expression' => 'blush'],
                4 => ['text' => '…また会えた。あなたがいると、なんだかすごくやる気が出るの。', 'expression' => 'love'],
            ],
            'laravel' => [
                0 => ['text' => 'あら、初めまして。私のことはLaravelって呼んで。丁寧に教えてあげるわ。', 'expression' => 'normal'],
                1 => ['text' => 'また来てくれたのね。Laravelの美しさ、少しずつ分かってきた？', 'expression' => 'smile'],
                2 => ['text' => 'ふふ、今日も来てくれたのね。一緒に優雅なコードを書きましょう。', 'expression' => 'happy'],
                3 => ['text' => '待ってたわ、明空くん。あなたが来ると、なんだか部屋が明るくなる気がして。', 'expression' => 'blush'],
                4 => ['text' => '…来てくれた。実はずっとここで待ってたの。変かしら？', 'expression' => 'love'],
            ],
            'error' => [
                0 => ['text' => 'ふん。お前のようなバグだらけの人間に用はないが…問題を解いてみせろ。', 'expression' => 'stern'],
                1 => ['text' => '前より少しマシになったな。まあ、問題を出してやろう。', 'expression' => 'normal'],
                2 => ['text' => '来たか。最近エラーが減ってきたな。成長を認めてやろう。', 'expression' => 'normal'],
                3 => ['text' => 'お前との問答も…悪くない。今日も一問だけ付き合ってやる。', 'expression' => 'smile'],
                4 => ['text' => '…来ると思っていた。なぜかお前のことが気になって仕方ない。', 'expression' => 'blush'],
            ],
        ];

        $default = [
            0 => ['text' => 'よろしく。一緒に頑張ろう。', 'expression' => 'normal'],
            1 => ['text' => 'また来てくれたんだね。', 'expression' => 'smile'],
            2 => ['text' => '来てくれると思ってたよ！', 'expression' => 'happy'],
            3 => ['text' => '会えて嬉しい。今日も頑張ろう。', 'expression' => 'blush'],
            4 => ['text' => '…ずっと待ってたよ。', 'expression' => 'love'],
        ];

        $map = $dialogues[$this->slug] ?? $default;
        return $map[$affectionLevel] ?? $map[0];
    }

    // 正解時のセリフ
    public function getCorrectDialogue(int $affectionLevel): array
    {
        $dialogues = [
            'php' => [
                0 => ['text' => '…正解。まあ、当然ね。', 'expression' => 'normal'],
                1 => ['text' => '正解よ！ちゃんと覚えてたんだ。', 'expression' => 'smile'],
                2 => ['text' => '正解〜！やるじゃない！えらいえらい♪', 'expression' => 'happy'],
                3 => ['text' => '正解！やっぱりあなたは飲み込みが早いわ。もしかして才能あるかも？', 'expression' => 'blush'],
                4 => ['text' => '正解っ！…ふふ、一緒に勉強してきた成果ね。なんか…嬉しい。', 'expression' => 'love'],
            ],
            'laravel' => [
                0 => ['text' => '正解ですわ。さすがに基礎はできているのね。', 'expression' => 'normal'],
                1 => ['text' => 'ふふ、正解。少しずつ上達してるわ。', 'expression' => 'smile'],
                2 => ['text' => '正解よ！素晴らしい。この調子でどんどん行きましょう！', 'expression' => 'happy'],
                3 => ['text' => '正解！…明空くん、最近本当に伸びてるわ。教えがいがある。ふふ。', 'expression' => 'blush'],
                4 => ['text' => '正解！あなたが正解するたびに、私まで嬉しくなってしまうの。おかしいかしら？', 'expression' => 'love'],
            ],
            'error' => [
                0 => ['text' => 'ふん。正解だ。…ビギナーズラックというやつか。', 'expression' => 'stern'],
                1 => ['text' => '正解。まあ、この程度は当然か。', 'expression' => 'normal'],
                2 => ['text' => '正解だ。…認めてやる。お前は本物だ。', 'expression' => 'normal'],
                3 => ['text' => '正解。…最近お前を問い詰めるのが少し楽しくなってきた。', 'expression' => 'smile'],
                4 => ['text' => '正解だ。…なぜだろう。お前が正解するとなんだか誇らしい気持ちになる。', 'expression' => 'blush'],
            ],
        ];

        $default = [
            0 => ['text' => '正解！', 'expression' => 'smile'],
            1 => ['text' => '正解！よくできました！', 'expression' => 'happy'],
            2 => ['text' => '正解〜！さすがだね！', 'expression' => 'happy'],
            3 => ['text' => '正解！最近すごく上手くなったね。', 'expression' => 'blush'],
            4 => ['text' => '正解！…一緒に頑張ってきた甲斐があった。', 'expression' => 'love'],
        ];

        $map = $dialogues[$this->slug] ?? $default;
        return $map[$affectionLevel] ?? $map[0];
    }

    // 不正解時のセリフ
    public function getWrongDialogue(int $affectionLevel): array
    {
        $dialogues = [
            'php' => [
                0 => ['text' => '不正解。もっとちゃんと勉強しなさい。', 'expression' => 'stern'],
                1 => ['text' => '惜しい！でも違うわ。解説をよく読んでね。', 'expression' => 'normal'],
                2 => ['text' => 'あら、間違えちゃった。大丈夫、一緒に確認しましょ？', 'expression' => 'smile'],
                3 => ['text' => '違うわ…でも大丈夫。あなたならすぐ覚えられる。私が保証する。', 'expression' => 'gentle'],
                4 => ['text' => '間違えちゃったね。…でも、こういう時こそ一緒に考えたいな。', 'expression' => 'love'],
            ],
            'laravel' => [
                0 => ['text' => '不正解ですわ。Laravelをなめてはいけないわ。', 'expression' => 'stern'],
                1 => ['text' => '惜しいけど不正解。解説をよく読んで覚えておいてね。', 'expression' => 'normal'],
                2 => ['text' => '残念！でも間違えるのも勉強よ。一緒に確認しましょ。', 'expression' => 'smile'],
                3 => ['text' => 'あら、間違えたの…。うふふ、そういうところも可愛いわ。次は一緒に考えましょ。', 'expression' => 'blush'],
                4 => ['text' => '不正解…。でも諦めないで。あなたが諦めたら、私が悲しい。', 'expression' => 'love'],
            ],
            'error' => [
                0 => ['text' => 'ふん。やはりその程度か。もっと鍛えてこい。', 'expression' => 'stern'],
                1 => ['text' => '不正解だ。解説をよく読め。次はないぞ。', 'expression' => 'stern'],
                2 => ['text' => '不正解。…まあ、難しい問題だったからな。次は正解しろ。', 'expression' => 'normal'],
                3 => ['text' => '不正解だ。…だが、めげるな。お前ならできると思っている。', 'expression' => 'gentle'],
                4 => ['text' => '不正解…。悔しいか？その気持ちを忘れるな。私はお前のことを信じている。', 'expression' => 'blush'],
            ],
        ];

        $default = [
            0 => ['text' => '不正解…。もう一度確認しよう。', 'expression' => 'normal'],
            1 => ['text' => '惜しい！次は頑張って。', 'expression' => 'normal'],
            2 => ['text' => '惜しかった！一緒に復習しよう。', 'expression' => 'smile'],
            3 => ['text' => 'ドンマイ！あなたならきっとできる。', 'expression' => 'gentle'],
            4 => ['text' => '間違えたね…でも大丈夫。一緒にいるから。', 'expression' => 'love'],
        ];

        $map = $dialogues[$this->slug] ?? $default;
        return $map[$affectionLevel] ?? $map[0];
    }
}
