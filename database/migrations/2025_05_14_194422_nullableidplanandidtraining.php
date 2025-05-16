<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // 1) rendre plan_id et training_id nullable
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('plan_id')->nullable()->change();
            $table->unsignedBigInteger('training_id')->nullable()->change();
        });

        // 2) corriger les données existantes
        //   a) si les deux colonnes sont NULL, on peut par exemple supprimer ces lignes
        //      ou, si tu préfères, forcer un champ à NULL et l'autre à une valeur par défaut.
        DB::table('orders')
            ->whereNull('plan_id')
            ->whereNull('training_id')
            ->delete();

        //   b) si les deux colonnes sont non-NULL, on garde uniquement plan_id (ou training_id)
        DB::table('orders')
            ->whereNotNull('plan_id')
            ->whereNotNull('training_id')
            ->update(['training_id' => null]);

        // 3) ajouter la contraint CHECK
        DB::statement(<<<SQL
ALTER TABLE `orders`
ADD CONSTRAINT `orders_plan_xor_training`
CHECK (
    (plan_id IS NOT NULL AND training_id IS NULL)
    OR
    (plan_id IS NULL AND training_id IS NOT NULL)
);
SQL
        );
    }

    public function down(): void
    {
        // Supprimer la check constraint
        DB::statement('ALTER TABLE `orders` DROP CHECK `orders_plan_xor_training`;');

        // Remettre les colonnes non-nullables si nécessaire
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('plan_id')->nullable(false)->change();
            $table->unsignedBigInteger('training_id')->nullable(false)->change();
        });
    }
};
