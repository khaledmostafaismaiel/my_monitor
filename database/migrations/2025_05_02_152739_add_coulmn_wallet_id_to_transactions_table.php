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
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedInteger('wallet_id')->nullable()->after('category_id');
        });

        App\Models\Family::each(function($family){
            $account = App\Models\Wallet::create(["name"=> "Cash", "family_id"=>$family->id, "status"=>"active"]);
            $family->normalTransactions()->update(["wallet_id"=>$account->id]);
            $family->draftTransactions()->update(["wallet_id"=>$account->id]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('wallet_id');
        });
    }
};
