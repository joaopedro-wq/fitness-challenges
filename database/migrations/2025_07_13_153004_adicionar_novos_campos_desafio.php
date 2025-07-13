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
       Schema::table('desafios', function (Blueprint $table) {
        
            $table->string('url_foto')->nullable()->after('duracao_dias');

            $table->enum('metodo_pontuacao', [
                'duracao',
                'distancia',
                'calorias',
                'dias_ativos'
            ])->default('dias_ativos')->after('url_foto');

            $table->float('meta_pontuacao')->nullable()->after('metodo_pontuacao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('desafios', function (Blueprint $table) {
            $table->dropColumn(['url_foto', 'metodo_pontuacao', 'meta_pontuacao']);
        });
    }
};
