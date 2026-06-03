#!/bin/bash
# 初回セットアップスクリプト
# 使い方: bash docker/setup.sh

set -e
cd "$(dirname "$0")/.."

echo "🌸 Cording Garden - Docker セットアップ"

# .env コピー
if [ ! -f .env ]; then
    cp .env.docker .env
    echo "✅ .env を作成しました"
fi

# コンテナ起動
docker compose up -d --build
echo "✅ コンテナ起動完了"

# DB が起動するまで待機
echo "⏳ データベース起動待機中..."
sleep 10

# Composer インストール
docker compose exec php composer install --no-interaction
echo "✅ Composer インストール完了"

# APP_KEY 生成
docker compose exec php php artisan key:generate
echo "✅ APP_KEY 生成完了"

# ストレージリンク
docker compose exec php php artisan storage:link

# マイグレーション＆シード
docker compose exec php php artisan migrate:fresh --seed --force
echo "✅ マイグレーション＆シード完了"

# 権限設定
docker compose exec php chmod -R 775 storage bootstrap/cache

echo ""
echo "🎉 セットアップ完了！"
echo "   ゲーム:       http://localhost"
echo "   管理画面:     http://localhost/admin"
echo "   テストアカウント:"
echo "     プレイヤー: player@cording-garden.test / password"
echo "     管理者:     admin@cording-garden.test / password"
