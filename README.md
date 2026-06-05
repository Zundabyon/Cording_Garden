# Cording Garden 🌸

プログラミング言語が美少女・美少年キャラクターに擬人化されたビジュアルノベル風学習ゲーム。  
PHP/Laravelを遊びながら習得しつつ、キャラクターとの恋愛シミュレーションを楽しめる。

---

## ゲーム概要

- **舞台**: 90日間の学校生活（3学期・1月8日〜）
- **目標**: プログラミングを学びながらキャラクターと仲を深める
- **ジャンル**: 恋愛シミュレーション × プログラミング学習

### ゲームサイクル

```
朝 → 午後 → 夜
 └─ 勉強（5問）→ 好感度UP → 締めセリフ → 次のフェーズへ
 └─ 休憩 / 外出 / 運動
```

### 登場キャラクター

| キャラ | モデル | 性格 |
|---|---|---|
| 比比野 穂香 | PHP | クール→ツンデレ、天才肌 |
| 神宮寺 らら美 | Laravel | 優雅・上品、お嬢様系 |
| 赤城 四朗 | Error | 無愛想→不器用な恋愛 |
| 日高 照瑠 | HTML | 誠実・真面目、敬語系 |
| 四季島 彩 | CSS | おしゃれ番長、センス重視 |
| 城島 翔 | JS | ツンデレ弟系 |
| 鷹峰 聖司 | TypeScript | 完璧主義・論理派 |
| 風見 来 | Vue | 英日ミックス、自由奔放 |

---

## 技術スタック

- **バックエンド**: Laravel 13 / PHP 8.3
- **フロントエンド**: Blade + Alpine.js + Tailwind CSS
- **DB**: SQLite（開発） / MySQL 8.0（Docker）
- **認証**: Laravel Breeze

---

## セットアップ

### ローカル（SQLite）

```bash
cp .env.example .env
composer install
npm install && npm run build
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

→ http://localhost:8000

### Docker

```bash
bash docker/setup.sh
```

→ http://localhost:80

初回セットアップ（`migrate:fresh --seed`）が自動実行される。  
以降は `docker compose up -d` のみでOK。

---

## 管理画面

`/admin` — 問題管理・キャラクター管理・ゲーム設定

管理者アカウントはSeederで作成される（`is_admin = true`のユーザー）。

---

## ゲーム設定（AdminSetting）

| キー | 初期値 | 説明 |
|---|---|---|
| `daily_study_limit` | 5 | 1セッションの問題数 |
| `hp_cost_study` | 10 | 勉強のHP消費 |
| `hp_recovery_rest` | 30 | 休憩のHP回復 |
| `affection_per_correct_answer` | 1 | 正解時の好感度（×難易度） |
| `max_affection_per_character` | 100 | 好感度上限 |

---

## 好感度システム

- 難易度1問 → +1、難易度2問 → +2、難易度3問 → +3
- 0〜100の好感度でセリフが変化（10点刻みで5バリアントからランダム選択）
- 5問完了で締めセリフ → フェーズ進行

---

## ディレクトリ構成（主要部分）

```
app/
├── Http/Controllers/
│   ├── ActionController.php   # アクション処理（勉強・休憩・外出）
│   ├── QuestionController.php # 回答処理・好感度更新
│   └── Admin/                 # 管理画面
├── Models/
│   ├── Character.php          # キャラクターモデル
│   ├── Affection.php          # 好感度モデル
│   └── GameSave.php           # セーブデータ
└── Services/
    ├── DialogueService.php    # セリフ管理（全キャラ・全好感度）
    └── GameService.php        # ゲームロジック

resources/views/game/
├── index.blade.php            # メイン画面
├── action-study.blade.php     # 問題画面
├── answer-result.blade.php    # 回答結果画面
└── study-complete.blade.php   # 5問完了画面
```
