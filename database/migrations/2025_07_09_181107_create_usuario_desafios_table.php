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
       Schema::create('usuario_desafios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('desafio_id')->constrained('desafios')->onDelete('cascade');
            $table->enum('status', ['em_andamento', 'concluido', 'falhou'])->default('em_andamento');
            $table->date('iniciado_em')->nullable();
            $table->date('concluido_em')->nullable();
            $table->integer('pontos_ganhos')->default(0);
            $table->timestamps();

            $table->unique(['usuario_id', 'desafio_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_desafios');
    }
};
