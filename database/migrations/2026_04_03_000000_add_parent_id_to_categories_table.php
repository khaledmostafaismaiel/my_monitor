<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use App\Models\Family;

class AddParentIdToCategoriesTable extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
        });

        $families = Family::all();
        foreach ($families as $family) {
            $existingCategories = Category::where('family_id', $family->id)->get();

            if ($existingCategories->isEmpty()) {
                Category::create([
                    'name' => 'Main',
                    'status' => 'active',
                    'family_id' => $family->id,
                ]);
                continue;
            }

            $mainCategory = Category::create([
                'name' => 'Main',
                'status' => 'active',
                'family_id' => $family->id,
            ]);

            Category::where('family_id', $family->id)
                ->where('id', '!=', $mainCategory->id)
                ->update(['parent_id' => $mainCategory->id]);
        }
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
}
