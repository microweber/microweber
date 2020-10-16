<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('name')->nullable();
            $table->string('phone')->nullable();


            $table->string('username')->unique();
            $table->string('email')->unique();



//            $table->string('facebook_id')->nullable();
//            $table->string('google_id')->nullable();
//            $table->string('github_id')->nullable();
       //     $table->string('contact_name')->nullable();
        //    $table->string('company_name')->nullable();

   //         $table->boolean('enable_portal')->nullable();

       //     $table->integer('currency_id')->unsigned()->nullable();
       //     $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');

          //  $table->integer('company_id')->unsigned()->nullable();
          //  $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

        });
    }

}
