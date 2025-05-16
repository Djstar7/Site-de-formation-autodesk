<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // Clé primaire auto-incrémentée
            $table->unsignedBigInteger('order_id'); // Référence à la commande associée
            $table->unsignedBigInteger('user_id'); // Référence à l'utilisateur qui a effectué le paiement
            $table->decimal('payment_price',8,2); // Prix de paiement
            $table->string('transaction_id')->nullable(); // ID de la transaction
            $table->enum('payment_status', ['pending', 'completed', 'failed'])->default('pending'); // Statut du paiement
            $table->timestamps();// Horodatage pour created_at et updated_at

            // Définir la clé étrangère pour relier la commande à un paiement
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            // Définir la clé étrangère pour relier l'utilisateur au paiement
            $table->foreign('user_id')->references('id')->on('usersone')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
