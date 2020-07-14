<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            //primary key
            $table->bigIncrements('id');

            //foreign keys columns
            $table->unsignedBigInteger('user_id');

            //normal table columns
            $table->string('title')->unique();

            //created_at and updated_at columns
            $table->timestamps();

            //add constraints
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('projects', 'user_id'))
        {
            Schema::table('projects', function (Blueprint $table) {

                $table->dropForeign('projects_user_id_foreign');
                $table->dropIndex('projects_user_id_foreign');

                // echo('(Custom Message 1: Dropped user_id foreign key constraint.)  ');
            });
        }

        Schema::dropIfExists('projects');
    }
}
