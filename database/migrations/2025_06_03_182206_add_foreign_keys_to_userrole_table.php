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
        Schema::table('userrole', function (Blueprint $table) {
            $table->foreign(['fkRoleid_Role'], 'fk_userrole_role')->references(['id_Role'])->on('role')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['fkUserid_User'], 'userrole_ibfk_1')->references(['id_User'])->on('user')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('userrole', function (Blueprint $table) {
            $table->dropForeign('fk_userrole_role');
            $table->dropForeign('userrole_ibfk_1');
        });
    }
};
