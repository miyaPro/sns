<?php

use Illuminate\Database\Seeder;

class MastersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('masters')->truncate();
        DB::table('masters')->insert(
            [
                [
                    'group'         => 'contract_status',
                    'code'          => '001',
                    'name_vn'       => 'Đang thực hiện',
                    'name_ja'       => '契約中',
                    'name_en'       => 'Processing',
                    'active_flg'    => '1',
                    'display_order' => '1'
                ],
                [
                    'group'         => 'contract_status',
                    'code'          => '002',
                    'name_vn'       => 'Hết hạn',
                    'name_ja'       => '解約済',
                    'name_en'       => 'Expired',
                    'active_flg'    => '1',
                    'display_order' => '2'
                ],
                [
                    'group'         => 'contract_status',
                    'code'          => '003',
                    'name_vn'       => 'Đã dừng',
                    'name_ja'       => '停止中',
                    'name_en'       => 'Stop',
                    'active_flg'    => '1',
                    'display_order' => '3'
                ],
                [
                    'group'         => 'contract_status',
                    'code'          => '004',
                    'name_vn'       => 'Kiểm tra',
                    'name_ja'       => '試用中',
                    'name_en'       => 'Test',
                    'active_flg'    => '1',
                    'display_order' => '4'
                ],
                [
                    'group'         => 'services',
                    'code'          => '001',
                    'name_vn'       => 'Facebook',
                    'name_ja'       => 'Facebook',
                    'name_en'       => 'Facebook',
                    'active_flg'    => '1',
                    'display_order' => '1'
                ],
                [
                    'group'         => 'services',
                    'code'          => '002',
                    'name_vn'       => 'Twitter',
                    'name_ja'       => 'Twitter',
                    'name_en'       => 'Twitter',
                    'active_flg'    => '1',
                    'display_order' => '2'
                ],
                [
                    'group'         => 'services',
                    'code'          => '003',
                    'name_vn'       => 'Instagram',
                    'name_ja'       => 'Instagram',
                    'name_en'       => 'Instagram',
                    'active_flg'    => '1',
                    'display_order' => '3'
                ],
                [
                    'group'         => 'services',
                    'code'          => '004',
                    'name_vn'       => 'Snapchat',
                    'name_ja'       => 'Snapchat',
                    'name_en'       => 'Snapchat',
                    'active_flg'    => '0',
                    'display_order' => '4'
                ]
            ]
        );
    }
}
