<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The table is named "equipment" (not pluralised) as the activity requires.
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();

            // Every record belongs to the user who created it.
            // restrictOnDelete keeps inventory history: a user who owns records cannot be removed.
            $table->foreignId('user_id')->constrained()->restrictOnDelete();

            $table->string('equipment_name');
            $table->string('category');                    // paddles | balls | nets | accessories
            $table->decimal('unit_price', 10, 2);          // exact money value, never float
            $table->text('description');
            $table->string('availability_status');         // available | unavailable
            $table->timestamps();

            // Reports filter on these two columns together.
            $table->index(['category', 'availability_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
