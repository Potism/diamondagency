<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertMapApiToExtensionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      DB::table('extensions')->insert([
              [
                  'act' => 'map_api',
                  'name' =>'Google Map API',
                  'description' => 'Key location is shown below',
                  'image' => 'google_map_api_icon.png',
                  'script' => '',
                  'shortcode' => '{"api_key":{"title":"Api Key","value":"AIzaSyBAZ8mNnqpZxYsRe5em2F-KxLn5UYsNLMg"}}',
                  'support' => 'google_map_api.jpg',
                  'status' => 1,
              ],
          ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('extensions', function (Blueprint $table) {
            //
        });
    }
}
