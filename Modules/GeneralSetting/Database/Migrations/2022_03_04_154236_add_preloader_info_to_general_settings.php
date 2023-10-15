<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\RolePermission\Entities\Permission;
use Modules\SidebarManager\Entities\Sidebar;

class AddPreloaderInfoToGeneralSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('general_settings')){
            Schema::table('general_settings', function (Blueprint $table) {
                $table->unsignedTinyInteger('preloader_type')->default(1)->nullable()->after('meta_description');
                $table->boolean('preloader_status')->default(1)->after('preloader_type');
                $table->unsignedInteger('preloader_style')->default(0)->after('preloader_status');
                $table->unsignedInteger('preloader_image')->nullable()->after('preloader_style');
            });
        }

        if(Schema::hasTable('permissions')){
            $sql = [
                //configuration
                ['id' => 703, 'module_id' => 4, 'parent_id' => 68, 'name' => 'Preloader Setting', 'route' => 'appearance.pre-loader', 'type' => 2 ],
                ['id' => 704, 'module_id' => 4, 'parent_id' => 703, 'name' => 'Update', 'route' => 'appearance.pre-loader.update', 'type' => 3 ]
            ];
            try{
                DB::table('permissions')->insert($sql);
            }catch(Exception $e){

            }
        }

        $sidebar_sql = [
            ['sidebar_id' => 198, 'module_id' => 3, 'parent_id' => 14,'position' => 7777, 'name' => 'Preloader Setting', 'route' => 'appearance.pre-loader', 'type' => 2]
        ];

        try{
            $users =  User::whereHas('role', function($query){
                $query->where('type', 'superadmin')->orWhere('type', 'admin')->orWhere('type', 'staff')->orWhere('type', 'seller');
            })->pluck('id');
            foreach ($users as $key=> $user)
            {
                $user_array[$key] = ['user_id' => $user];
                foreach ($sidebar_sql as $row)
                {
                    $final_row = array_merge($user_array[$key],$row);
                    Sidebar::insert($final_row);
                }
            }
        }catch(Exception $e){

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn('preloader_type');
            $table->dropColumn('preloader_status');
            $table->dropColumn('preloader_style');
            $table->dropColumn('preloader_image');
        });

        Permission::destroy([701,702]);
        $ids = Sidebar::where('sidebar_id', 198)->pluck('id')->toArray();
        Sidebar::destroy($ids);
    }
}
